<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ t('tables.title') }}</h1>
      <button @click="showAddModal = true" class="px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors">
        {{ t('tables.add') }}
      </button>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="animate-spin h-8 w-8 border-4 border-orange-500 border-t-transparent rounded-full"></div>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="table in tables" :key="table.id" class="bg-white rounded-2xl shadow-sm p-4 flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold text-gray-800">{{ table.name }}</h3>
          <span :class="table.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="text-xs px-2 py-0.5 rounded-full font-medium">
            {{ table.active ? t('tables.active') : t('tables.inactive') }}
          </span>
        </div>

        <div class="flex justify-center py-2">
          <canvas :id="`qr-${table.id}`" class="w-32 h-32"></canvas>
        </div>

        <p class="text-xs text-gray-400 text-center break-all">{{ menuUrl(table.token) }}</p>

        <div class="flex gap-2">
          <button @click="downloadQr(table)" class="flex-1 py-2 border border-orange-200 text-orange-600 rounded-xl text-sm font-medium hover:bg-orange-50 transition-colors">
            {{ t('tables.download') }}
          </button>
          <button @click="toggleTable(table)" class="flex-1 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
            {{ table.active ? t('tables.deactivate') : t('tables.activate') }}
          </button>
          <button @click="deleteTable(table.id)" class="py-2 px-3 border border-red-200 text-red-500 rounded-xl text-sm hover:bg-red-50 transition-colors">
            🗑
          </button>
        </div>
      </div>
    </div>

    <!-- Add modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl">
        <h2 class="font-bold text-lg mb-4">{{ t('tables.add_title') }}</h2>
        <input
          v-model="newTableName"
          type="text"
          :placeholder="t('tables.table_name_placeholder')"
          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 mb-4"
        />
        <div class="flex gap-3">
          <button @click="showAddModal = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600">{{ t('common.cancel') }}</button>
          <button @click="addTable" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold">{{ t('common.add') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'
import QRCode from 'qrcode'
import type { QrTable } from '@/types'
import http from '@/api/http'
import { useAuthStore } from '../stores/authStore'

const { t } = useI18n()
const auth = useAuthStore()
const tables = ref<QrTable[]>([])
const loading = ref(true)
const showAddModal = ref(false)
const newTableName = ref('')

onMounted(fetchTables)

async function fetchTables() {
  loading.value = true
  try {
    const res = await http.get('/admin/tables')
    tables.value = res.data.data
    await nextTick()
    renderQrCodes()
  } finally {
    loading.value = false
  }
}

function menuUrl(token: string) {
  const slug = auth.tenant?.slug ?? ''
  const lang = auth.tenant?.primary_lang ?? 'tr'
  return `${auth.appUrl}/${slug}/masa/${token}?lang=${lang}`
}

function renderQrCodes() {
  tables.value.forEach((t) => {
    const canvas = document.getElementById(`qr-${t.id}`) as HTMLCanvasElement
    if (canvas) QRCode.toCanvas(canvas, menuUrl(t.token), { width: 128, margin: 1 })
  })
}

async function addTable() {
  if (!newTableName.value.trim()) return
  await http.post('/admin/tables', { name: newTableName.value.trim() })
  newTableName.value = ''
  showAddModal.value = false
  await fetchTables()
}

async function toggleTable(table: QrTable) {
  await http.patch(`/admin/tables/${table.id}`, { active: !table.active })
  table.active = !table.active
}

async function deleteTable(id: number) {
  if (!confirm(t('tables.confirm_delete'))) return
  await http.delete(`/admin/tables/${id}`)
  tables.value = tables.value.filter((t) => t.id !== id)
}

async function downloadQr(table: QrTable) {
  const url = menuUrl(table.token)
  const restaurantName = auth.tenant?.name ?? ''

  const qrSize = 512
  const padding = 40
  const headerHeight = 100
  const footerHeight = 80
  const totalWidth = qrSize + padding * 2
  const totalHeight = qrSize + padding * 2 + headerHeight + footerHeight

  const canvas = document.createElement('canvas')
  canvas.width = totalWidth
  canvas.height = totalHeight
  const ctx = canvas.getContext('2d')!
  ctx.fillStyle = '#ffffff'
  ctx.fillRect(0, 0, totalWidth, totalHeight)

  const qrCanvas = document.createElement('canvas')
  await QRCode.toCanvas(qrCanvas, url, { width: qrSize, margin: 0, color: { dark: '#000000', light: '#ffffff' } })
  ctx.drawImage(qrCanvas, padding, padding + headerHeight, qrSize, qrSize)

  ctx.fillStyle = '#111111'
  ctx.textAlign = 'center'
  ctx.font = 'bold 36px sans-serif'
  ctx.fillText(restaurantName, totalWidth / 2, padding + 42)
  ctx.font = '28px sans-serif'
  ctx.fillText(table.name, totalWidth / 2, padding + 84)

  ctx.font = '20px monospace'
  ctx.fillStyle = '#555555'
  ctx.fillText(url, totalWidth / 2, padding + headerHeight + qrSize + 36, totalWidth - padding * 2)

  const a = document.createElement('a')
  a.download = `${table.name}-qr.png`
  a.href = canvas.toDataURL('image/png')
  a.click()
}
</script>
