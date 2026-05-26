<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ t('departments.title') }}</h1>
      <button @click="openAdd" class="px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors">
        {{ t('departments.add') }}
      </button>
    </div>

    <div v-if="loading" class="text-center py-12 text-gray-400">{{ t('common.loading') }}</div>

    <div v-else-if="departments.length === 0" class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400">
      {{ t('departments.empty') }}
    </div>

    <div v-else class="space-y-3">
      <div
        v-for="dept in departments"
        :key="dept.id"
        class="bg-white rounded-2xl shadow-sm p-4 flex items-center gap-4"
      >
        <!-- Logo -->
        <img v-if="dept.logo" :src="dept.logo" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-gray-100" />
        <div v-else class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>
          </svg>
        </div>

        <div class="flex-1 min-w-0">
          <p class="font-semibold text-gray-900">{{ dept.display_name || dept.name }}</p>
          <p class="text-xs text-gray-400 mt-0.5">
            {{ dept.name }} · {{ t('departments.multiplier') }}: ×{{ dept.price_multiplier.toFixed(2) }}
          </p>
        </div>

        <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
          <template v-if="departments.length > 1">
            <button @click="openCategories(dept)" class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
              {{ t('departments.categories_btn') }}
            </button>
            <button @click="openProducts(dept)" class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
              {{ t('departments.products_btn') }}
            </button>
          </template>
          <button @click="openEdit(dept)" class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            {{ t('common.save') === 'Kaydet' ? 'Düzenle' : 'Edit' }}
          </button>
          <button @click="copyLink(dept)" class="px-3 py-1.5 text-xs font-medium border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
            QR Link
          </button>
          <button @click="downloadQrPdf(dept)" class="px-3 py-1.5 text-xs font-medium border border-green-200 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
            PDF İndir
          </button>
          <button @click="downloadQrJpg(dept)" class="px-3 py-1.5 text-xs font-medium border border-purple-200 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors">
            JPG İndir
          </button>
          <button
            @click="toggleActive(dept)"
            class="relative w-11 h-6 rounded-full transition-colors"
            :class="dept.active ? 'bg-orange-500' : 'bg-gray-200'"
          >
            <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="dept.active ? 'translate-x-6' : 'translate-x-1'"></span>
          </button>
          <button @click="deleteDept(dept)" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Departman Ekle/Düzenle Modal -->
  <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" @click.self="showForm = false">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 max-h-[90vh] overflow-y-auto">
      <h2 class="font-bold text-gray-900 mb-4">{{ editing ? t('departments.edit_title') : t('departments.add_title') }}</h2>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('departments.name') }}</label>
          <input v-model="formData.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('departments.display_name') }}</label>
          <p class="text-xs text-gray-400 mb-1.5">{{ t('departments.display_name_desc') }}</p>
          <input v-model="formData.display_name" type="text" :placeholder="formData.name" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('departments.logo') }}</label>
          <p class="text-xs text-gray-400 mb-1.5">{{ t('departments.logo_desc') }}</p>
          <div class="flex items-center gap-3">
            <img v-if="formData.logo" :src="formData.logo" class="w-14 h-14 rounded-xl object-cover border border-gray-200 shrink-0" />
            <div v-else class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 text-gray-400 text-xs text-center">Logo</div>
            <div class="flex-1">
              <label class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 text-sm text-gray-600 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                {{ logoUploading ? t('common.saving') : t('departments.logo_upload') }}
                <input type="file" accept="image/*" class="hidden" @change="uploadLogo" :disabled="logoUploading" />
              </label>
              <button v-if="formData.logo" @click="formData.logo = ''" class="mt-1.5 text-xs text-red-400 hover:text-red-600">{{ t('departments.logo_remove') }}</button>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('departments.banner_image') }}</label>
          <p class="text-xs text-gray-400 mb-1.5">{{ t('departments.banner_image_desc') }}</p>
          <div class="flex items-center gap-3">
            <img v-if="formData.banner_image" :src="formData.banner_image" class="w-20 h-12 rounded-xl object-cover border border-gray-200 shrink-0" />
            <div v-else class="w-20 h-12 rounded-xl bg-gray-100 flex items-center justify-center shrink-0 text-gray-400 text-xs text-center">Banner</div>
            <div class="flex-1">
              <label class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 text-sm text-gray-600 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                {{ bannerUploading ? t('common.saving') : t('departments.banner_upload') }}
                <input type="file" accept="image/*" class="hidden" @change="uploadBanner" :disabled="bannerUploading" />
              </label>
              <button v-if="formData.banner_image" @click="formData.banner_image = ''" class="mt-1.5 text-xs text-red-400 hover:text-red-600">{{ t('departments.banner_remove') }}</button>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('departments.multiplier_label') }}</label>
          <p class="text-xs text-gray-400 mb-1.5">{{ t('departments.multiplier_desc') }}</p>
          <input v-model.number="formData.price_multiplier" type="number" step="0.01" min="0.01" max="99.99" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
        </div>
      </div>
      <div class="flex gap-2 mt-5">
        <button @click="showForm = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-medium hover:bg-gray-50">{{ t('common.cancel') }}</button>
        <button @click="saveForm" :disabled="saving" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 disabled:opacity-60">
          {{ saving ? t('common.saving') : t('common.save') }}
        </button>
      </div>
    </div>
  </div>

  <!-- Kategori Görünürlük Modal -->
  <div v-if="showCategories" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" @click.self="showCategories = false">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col max-h-[85vh]">
      <div class="p-5 border-b flex items-center justify-between">
        <h2 class="font-bold text-gray-900">{{ t('departments.categories_title') }}: {{ selectedDept?.display_name || selectedDept?.name }}</h2>
        <button @click="showCategories = false" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>
      <p class="px-5 pt-3 text-xs text-gray-400">{{ t('departments.categories_desc') }}</p>

      <div v-if="categoriesLoading" class="p-8 text-center text-gray-400">{{ t('common.loading') }}</div>

      <div v-else class="flex-1 overflow-y-auto p-4 space-y-1">
        <template v-for="cat in categoryOverrides" :key="cat.id">
          <!-- Root category -->
          <div
            v-if="cat.parent_id === null"
            class="flex items-center justify-between px-3 py-2.5 rounded-xl"
            :class="cat.hidden ? 'bg-red-50' : 'bg-gray-50'"
          >
            <span class="text-sm font-semibold text-gray-800">{{ cat.name }}</span>
            <button
              @click="cat.hidden = !cat.hidden"
              class="relative w-10 h-5 rounded-full transition-colors shrink-0"
              :class="cat.hidden ? 'bg-red-400' : 'bg-orange-500'"
              :title="cat.hidden ? t('departments.show_category') : t('departments.hide_category')"
            >
              <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="cat.hidden ? 'translate-x-0.5' : 'translate-x-5'"></span>
            </button>
          </div>
          <!-- Sub category -->
          <div
            v-else
            class="flex items-center justify-between px-3 py-2 ml-5 rounded-xl border border-gray-100"
            :class="cat.hidden ? 'bg-red-50 opacity-70' : ''"
          >
            <span class="text-xs text-gray-600 flex items-center gap-1.5">
              <span class="text-gray-300">└</span> {{ cat.name }}
            </span>
            <button
              @click="cat.hidden = !cat.hidden"
              class="relative w-9 h-5 rounded-full transition-colors shrink-0"
              :class="cat.hidden ? 'bg-red-400' : 'bg-orange-400'"
              :title="cat.hidden ? t('departments.show_category') : t('departments.hide_category')"
            >
              <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="cat.hidden ? 'translate-x-0.5' : 'translate-x-4'"></span>
            </button>
          </div>
        </template>
      </div>

      <div class="p-4 border-t flex gap-2">
        <button @click="showCategories = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-medium hover:bg-gray-50">{{ t('common.cancel') }}</button>
        <button @click="saveCategories" :disabled="savingCategories" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 disabled:opacity-60">
          {{ savingCategories ? t('common.saving') : t('common.save') }}
        </button>
      </div>
    </div>
  </div>

  <!-- Ürün Görünürlük Modal -->
  <div v-if="showProducts" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" @click.self="showProducts = false">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col max-h-[85vh]">
      <div class="p-5 border-b flex items-center justify-between">
        <h2 class="font-bold text-gray-900">{{ t('departments.products_title') }}: {{ selectedDept?.display_name || selectedDept?.name }}</h2>
        <button @click="showProducts = false" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>

      <div v-if="productsLoading" class="p-8 text-center text-gray-400">{{ t('common.loading') }}</div>

      <div v-else class="flex-1 overflow-y-auto p-4 space-y-2">
        <div
          v-for="p in productOverrides"
          :key="p.id"
          class="flex items-center gap-3 p-3 rounded-xl border border-gray-100"
          :class="p.hidden ? 'opacity-50 bg-gray-50' : ''"
        >
          <img v-if="p.image" :src="p.image" class="w-10 h-10 rounded-lg object-cover shrink-0" />
          <div v-else class="w-10 h-10 rounded-lg bg-gray-100 shrink-0"></div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ p.name }}</p>
            <p class="text-xs text-gray-400">{{ t('departments.base_price') }}: {{ formatPrice(p.price) }}</p>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <input
              v-model.number="p.override_price"
              type="number"
              step="0.01"
              min="0"
              :placeholder="t('departments.override_price_ph')"
              class="w-24 text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-orange-300"
            />
            <button
              @click="p.hidden = !p.hidden"
              class="relative w-10 h-5 rounded-full transition-colors shrink-0"
              :class="p.hidden ? 'bg-gray-300' : 'bg-orange-500'"
              :title="p.hidden ? t('departments.show_product') : t('departments.hide_product')"
            >
              <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="p.hidden ? 'translate-x-0.5' : 'translate-x-5'"></span>
            </button>
          </div>
        </div>
      </div>

      <div class="p-4 border-t flex gap-2">
        <button @click="showProducts = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm font-medium hover:bg-gray-50">{{ t('common.cancel') }}</button>
        <button @click="saveProducts" :disabled="savingProducts" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 disabled:opacity-60">
          {{ savingProducts ? t('common.saving') : t('common.save') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../stores/authStore'
import type { Department, DepartmentProduct, DepartmentCategoryItem } from '@/types'
import http from '@/api/http'
import { useToast } from '../composables/useToast'

const { t } = useI18n()
const auth = useAuthStore()
const { success } = useToast()

const departments = ref<Department[]>([])
const loading = ref(true)
const saving = ref(false)
const savingProducts = ref(false)
const savingCategories = ref(false)
const logoUploading = ref(false)

const showForm = ref(false)
const editing = ref<Department | null>(null)
const formData = ref({ name: '', display_name: '', logo: '', banner_image: '', price_multiplier: 1.00 })
const bannerUploading = ref(false)

const showProducts = ref(false)
const showCategories = ref(false)
const selectedDept = ref<Department | null>(null)
const productOverrides = ref<DepartmentProduct[]>([])
const categoryOverrides = ref<DepartmentCategoryItem[]>([])
const productsLoading = ref(false)
const categoriesLoading = ref(false)

onMounted(load)

async function load() {
  loading.value = true
  try {
    const res = await http.get('/admin/departments')
    departments.value = res.data.data
  } finally {
    loading.value = false
  }
}

function openAdd() {
  editing.value = null
  formData.value = { name: '', display_name: '', logo: '', banner_image: '', price_multiplier: 1.00 }
  showForm.value = true
}

function openEdit(dept: Department) {
  editing.value = dept
  formData.value = {
    name: dept.name,
    display_name: dept.display_name ?? '',
    logo: dept.logo ?? '',
    banner_image: dept.banner_image ?? '',
    price_multiplier: dept.price_multiplier,
  }
  showForm.value = true
}

async function uploadLogo(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return
  logoUploading.value = true
  try {
    const fd = new FormData()
    fd.append('image', file)
    const res = await http.post('/admin/upload-image', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    formData.value.logo = res.data.url
  } finally {
    logoUploading.value = false
  }
}

async function uploadBanner(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return
  bannerUploading.value = true
  try {
    const fd = new FormData()
    fd.append('image', file)
    const res = await http.post('/admin/upload-image', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    formData.value.banner_image = res.data.url
  } finally {
    bannerUploading.value = false
  }
}

async function saveForm() {
  if (!formData.value.name.trim()) return
  saving.value = true
  try {
    const payload = {
      name: formData.value.name,
      display_name: formData.value.display_name || null,
      logo: formData.value.logo || null,
      banner_image: formData.value.banner_image || null,
      price_multiplier: formData.value.price_multiplier,
    }
    if (editing.value) {
      await http.patch(`/admin/departments/${editing.value.id}`, payload)
    } else {
      await http.post('/admin/departments', payload)
    }
    showForm.value = false
    await load()
  } finally {
    saving.value = false
  }
}

async function toggleActive(dept: Department) {
  await http.patch(`/admin/departments/${dept.id}`, { active: !dept.active })
  dept.active = !dept.active
}

async function deleteDept(dept: Department) {
  if (!confirm(t('departments.confirm_delete'))) return
  await http.delete(`/admin/departments/${dept.id}`)
  departments.value = departments.value.filter(d => d.id !== dept.id)
}

function deptMenuUrl(dept: Department) {
  const slug = auth.tenant?.slug ?? ''
  const lang = auth.tenant?.primary_lang ?? 'tr'
  if (departments.value.length === 1) {
    return `${auth.appUrl}/${slug}?lang=${lang}`
  }
  return `${auth.appUrl}/${slug}/dept/${dept.token}?lang=${lang}`
}

function copyLink(dept: Department) {
  const url = deptMenuUrl(dept)
  navigator.clipboard.writeText(url).then(() => success(url))
}

async function openCategories(dept: Department) {
  selectedDept.value = dept
  showCategories.value = true
  categoriesLoading.value = true
  try {
    const res = await http.get(`/admin/departments/${dept.id}/categories`)
    categoryOverrides.value = res.data.data
  } finally {
    categoriesLoading.value = false
  }
}

async function saveCategories() {
  if (!selectedDept.value) return
  savingCategories.value = true
  try {
    await http.put(`/admin/departments/${selectedDept.value.id}/categories`, {
      overrides: categoryOverrides.value.map(c => ({
        category_id: c.id,
        hidden: c.hidden,
      })),
    })
    showCategories.value = false
  } finally {
    savingCategories.value = false
  }
}

async function downloadQrJpg(dept: Department) {
  const url = deptMenuUrl(dept)
  const restaurantName = auth.tenant?.name ?? ''
  const QRCode = await import('qrcode')

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
  ctx.fillText(dept.display_name || dept.name, totalWidth / 2, padding + 84)

  ctx.font = '20px monospace'
  ctx.fillStyle = '#555555'
  const urlY = padding + headerHeight + qrSize + 36
  const maxWidth = totalWidth - padding * 2
  ctx.fillText(url, totalWidth / 2, urlY, maxWidth)

  const a = document.createElement('a')
  a.download = `${dept.name}-qr.jpg`
  a.href = canvas.toDataURL('image/jpeg', 0.95)
  a.click()
}

async function downloadQrPdf(dept: Department) {
  const url = deptMenuUrl(dept)
  const restaurantName = auth.tenant?.name ?? ''
  const [QRCode, { jsPDF }] = await Promise.all([import('qrcode'), import('jspdf')])
  const dataUrl = await QRCode.toDataURL(url, { width: 512, margin: 2 })

  const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' })
  const pageW = 210
  const qrSize = 120
  const qrX = (pageW - qrSize) / 2
  const qrY = 70

  pdf.setFont('helvetica', 'bold')
  pdf.setFontSize(22)
  pdf.text(restaurantName, pageW / 2, 30, { align: 'center' })

  pdf.setFont('helvetica', 'normal')
  pdf.setFontSize(15)
  pdf.text(dept.display_name || dept.name, pageW / 2, 45, { align: 'center' })

  pdf.addImage(dataUrl, 'PNG', qrX, qrY, qrSize, qrSize)

  pdf.setFontSize(9)
  pdf.setTextColor(80, 80, 80)
  pdf.text(url, pageW / 2, qrY + qrSize + 12, { align: 'center', maxWidth: pageW - 20 })

  pdf.save(`${dept.name}-qr.pdf`)
}

async function openProducts(dept: Department) {
  selectedDept.value = dept
  showProducts.value = true
  productsLoading.value = true
  try {
    const res = await http.get(`/admin/departments/${dept.id}/products`)
    productOverrides.value = res.data.data
  } finally {
    productsLoading.value = false
  }
}

async function saveProducts() {
  if (!selectedDept.value) return
  savingProducts.value = true
  try {
    await http.put(`/admin/departments/${selectedDept.value.id}/products`, {
      overrides: productOverrides.value.map(p => ({
        product_id: p.id,
        hidden: p.hidden,
        price: p.override_price ?? null,
      })),
    })
    showProducts.value = false
  } finally {
    savingProducts.value = false
  }
}

function formatPrice(amount: number) {
  return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount)
}
</script>
