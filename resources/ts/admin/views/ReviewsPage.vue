<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Yorumlar & Şikayetler</h1>
      <div class="flex gap-2">
        <button
          v-for="f in filters"
          :key="f.value"
          @click="activeFilter = f.value"
          class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors"
          :class="activeFilter === f.value ? 'bg-orange-500 text-white' : 'bg-white text-gray-600 border border-gray-200'"
        >{{ f.label }}</button>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="animate-spin h-8 w-8 border-4 border-orange-500 border-t-transparent rounded-full"></div>
    </div>

    <div v-else-if="filtered.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400">
      Bu filtrede kayıt yok.
    </div>

    <div v-else class="space-y-3">
      <div
        v-for="r in filtered"
        :key="r.id"
        class="bg-white rounded-2xl shadow-sm p-4"
        :class="r.type === 'complaint' ? 'border-l-4 border-red-400' : 'border-l-4 border-orange-400'"
      >
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <span class="text-xs font-semibold px-2 py-0.5 rounded-full" :class="r.type === 'complaint' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600'">
                {{ r.type === 'complaint' ? '⚠️ Şikayet' : '⭐ Yorum' }}
              </span>
              <span v-if="r.rating" class="text-yellow-500 text-sm font-bold">
                {{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}
              </span>
              <span v-if="r.name" class="text-sm text-gray-500 font-medium">— {{ r.name }}</span>
              <span v-if="r.table" class="text-xs text-gray-400">📍 {{ r.table.name }}</span>
            </div>
            <p v-if="r.comment" class="text-sm text-gray-700 mt-1">{{ r.comment }}</p>
            <p v-else class="text-sm text-gray-400 italic mt-1">Yorum yazılmamış.</p>
            <p class="text-xs text-gray-400 mt-2">{{ formatDate(r.created_at) }}</p>
          </div>
          <button
            @click="deleteReview(r)"
            class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import http from '@/api/http'
import { useConfirm } from '../composables/useConfirm'

interface ReviewItem {
  id: number
  type: 'review' | 'complaint'
  rating: number | null
  comment: string | null
  name: string | null
  table: { id: number; name: string } | null
  created_at: string
}

const { confirm } = useConfirm()
const reviews = ref<ReviewItem[]>([])
const loading = ref(true)
const activeFilter = ref<'all' | 'review' | 'complaint'>('all')

const filters = [
  { value: 'all', label: 'Tümü' },
  { value: 'review', label: '⭐ Yorumlar' },
  { value: 'complaint', label: '⚠️ Şikayetler' },
] as const

const filtered = computed(() =>
  activeFilter.value === 'all' ? reviews.value : reviews.value.filter(r => r.type === activeFilter.value)
)

onMounted(load)

async function load() {
  loading.value = true
  try {
    const res = await http.get('/admin/reviews')
    reviews.value = res.data.data
  } finally {
    loading.value = false
  }
}

async function deleteReview(r: ReviewItem) {
  if (!await confirm('Bu kaydı silmek istiyor musunuz?', { title: 'Yorumu Sil', confirmText: 'Sil', type: 'danger' })) return
  await http.delete(`/admin/reviews/${r.id}`)
  reviews.value = reviews.value.filter(x => x.id !== r.id)
}

function formatDate(s: string) {
  return new Date(s).toLocaleString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
</script>
