<template>
  <div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ t('categories.title') }}</h1>
      <div class="flex items-center gap-2">
        <button
          @click="autoTranslate"
          :disabled="translating"
          class="px-4 py-2 border border-blue-200 text-blue-600 rounded-xl text-sm font-medium hover:bg-blue-50 disabled:opacity-60 transition-colors"
        >
          <span v-if="translating" class="flex items-center gap-1.5">
            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            {{ t('products.translating') }}
          </span>
          <span v-else>{{ t('products.auto_translate') }}</span>
        </button>
        <button @click="openModal()" class="px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors">
          {{ t('categories.add') }}
        </button>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="animate-spin h-8 w-8 border-4 border-orange-500 border-t-transparent rounded-full"></div>
    </div>

    <div v-else class="space-y-2">
      <template v-for="cat in categories" :key="cat.id">
        <!-- Root category -->
        <div class="bg-white rounded-2xl shadow-sm px-4 py-3 flex items-center gap-4">
          <img v-if="cat.image" :src="cat.image" class="w-10 h-10 rounded-xl object-cover shrink-0" />
          <div v-else class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500 font-bold shrink-0">
            {{ cat.name[0] }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-medium text-gray-800">{{ cat.name }}</p>
            <p class="text-xs text-gray-400">
              {{ t('common.sort_order') }}: {{ cat.sort_order }}
              <span v-if="cat.span === 'wide'" class="ml-2 px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded text-xs">{{ t('categories.wide') }}</span>
            </p>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button @click="toggleActive(cat)" class="relative w-10 h-5 rounded-full transition-colors" :class="cat.active ? 'bg-green-500' : 'bg-gray-300'">
              <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="cat.active ? 'right-0.5' : 'left-0.5'"></span>
            </button>
            <button @click="openModal(cat)" class="p-1.5 text-gray-400 hover:text-orange-500 rounded-lg">✏️</button>
            <button @click="deleteCategory(cat.id)" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg">🗑</button>
          </div>
        </div>

        <!-- Children (subcategories) -->
        <div v-for="child in cat.children" :key="child.id" class="bg-gray-50 rounded-2xl shadow-sm px-4 py-3 flex items-center gap-4 ml-6 border border-gray-100">
          <img v-if="child.image" :src="child.image" class="w-8 h-8 rounded-lg object-cover shrink-0" />
          <div v-else class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold shrink-0">
            {{ child.name[0] }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-700">↳ {{ child.name }}</p>
            <p class="text-xs text-gray-400">{{ t('common.sort_order') }}: {{ child.sort_order }}</p>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button @click="toggleActive(child)" class="relative w-10 h-5 rounded-full transition-colors" :class="child.active ? 'bg-green-500' : 'bg-gray-300'">
              <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="child.active ? 'right-0.5' : 'left-0.5'"></span>
            </button>
            <button @click="openModal(child)" class="p-1.5 text-gray-400 hover:text-orange-500 rounded-lg">✏️</button>
            <button @click="deleteCategory(child.id)" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg">🗑</button>
          </div>
        </div>
      </template>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="font-bold text-lg mb-5">{{ editing ? t('categories.edit_title') : t('categories.add_title') }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('categories.name') }}</label>
            <input v-model="form.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('categories.parent_label') }}</label>
            <select v-model="form.parent_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
              <option :value="null">{{ t('categories.no_parent') }}</option>
              <option v-for="cat in rootCategories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('categories.sort_order') }}</label>
            <input v-model.number="form.sort_order" type="number" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('categories.image_url') }}</label>
            <ImageUploader v-model="form.image" type="categories" />
          </div>
          <div v-if="!form.parent_id">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('categories.span_label') }}</label>
            <div class="flex gap-3">
              <button
                @click="form.span = 'normal'"
                class="flex-1 py-2 rounded-xl text-sm font-medium border transition-colors"
                :class="form.span === 'normal' ? 'bg-orange-500 text-white border-orange-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
              >
                {{ t('categories.span_normal') }}
              </button>
              <button
                @click="form.span = 'wide'"
                class="flex-1 py-2 rounded-xl text-sm font-medium border transition-colors"
                :class="form.span === 'wide' ? 'bg-orange-500 text-white border-orange-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
              >
                {{ t('categories.span_wide') }}
              </button>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ t('categories.span_desc') }}</p>
          </div>

          <!-- Çeviri sekmeleri -->
          <div v-if="activeLangs.length > 0">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('categories.translations_label') }}</label>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
              <div class="flex border-b border-gray-200 overflow-x-auto">
                <button
                  v-for="lang in activeLangs"
                  :key="lang.lang_code"
                  @click="activeTab = lang.lang_code"
                  class="px-3 py-2 text-xs font-medium whitespace-nowrap transition-colors"
                  :class="activeTab === lang.lang_code ? 'bg-orange-50 text-orange-600 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                >
                  {{ getLangMeta(lang.lang_code)?.flag }} {{ lang.lang_code.toUpperCase() }}
                </button>
              </div>
              <div class="p-3">
                <div v-for="lang in activeLangs" :key="lang.lang_code" v-show="activeTab === lang.lang_code">
                  <label class="block text-xs text-gray-500 mb-1">{{ getLangMeta(lang.lang_code)?.label }}</label>
                  <input
                    v-model="form.translations[lang.lang_code].name"
                    type="text"
                    :placeholder="`${form.name || t('categories.name')} (${lang.lang_code})`"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-3 mt-6">
          <button @click="showModal = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600">{{ t('common.cancel') }}</button>
          <button @click="save" :disabled="saving" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold disabled:opacity-60">
            {{ saving ? t('common.saving') : t('common.save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Category, TenantLanguage } from '@/types'
import { ADMIN_LANGUAGES } from '../i18n'
import http from '@/api/http'
import { useToast } from '../composables/useToast'
import ImageUploader from '../components/ImageUploader.vue'

const { t } = useI18n()
const { success, error: toastError } = useToast()
const categories = ref<Category[]>([])
const activeLangs = ref<TenantLanguage[]>([])
const loading = ref(true)
const translating = ref(false)
const showModal = ref(false)
const saving = ref(false)
const editing = ref<Category | null>(null)
const activeTab = ref('')

const rootCategories = computed(() => categories.value)

const form = ref({
  name: '', sort_order: 0, image: '',
  parent_id: null as number | null,
  span: 'normal' as 'normal' | 'wide',
  translations: {} as Record<string, { name: string }>,
})

function getLangMeta(code: string) {
  return ADMIN_LANGUAGES.find((l) => l.code === code)
}

function buildTranslations() {
  const t: Record<string, { name: string }> = {}
  activeLangs.value.forEach((l) => { t[l.lang_code] = { name: '' } })
  return t
}

onMounted(async () => {
  const [catRes, langRes] = await Promise.all([http.get('/admin/categories'), http.get('/admin/languages')])
  categories.value = catRes.data.data
  activeLangs.value = (langRes.data.data as TenantLanguage[]).filter((l) => l.active)
  if (activeLangs.value.length) activeTab.value = activeLangs.value[0].lang_code
  loading.value = false
})

async function fetchCategories() {
  loading.value = true
  const res = await http.get('/admin/categories')
  categories.value = res.data.data
  loading.value = false
}

function openModal(cat?: Category) {
  editing.value = cat ?? null
  const translations = buildTranslations()
  if (cat?.translations) {
    activeLangs.value.forEach((l) => {
      translations[l.lang_code] = { name: cat.translations?.[l.lang_code]?.name ?? '' }
    })
  }
  form.value = {
    name: cat?.name ?? '',
    sort_order: cat?.sort_order ?? 0,
    image: cat?.image ?? '',
    parent_id: cat?.parent_id ?? null,
    span: (cat?.span ?? 'normal') as 'normal' | 'wide',
    translations,
  }
  if (activeLangs.value.length) activeTab.value = activeLangs.value[0].lang_code
  showModal.value = true
}

async function save() {
  saving.value = true
  try {
    const payload = {
      name: form.value.name,
      sort_order: form.value.sort_order,
      image: form.value.image || null,
      parent_id: form.value.parent_id ?? null,
      span: form.value.span,
      translations: Object.entries(form.value.translations)
        .filter(([, t]) => t.name.trim() !== '')
        .map(([lang_code, t]) => ({ lang_code, name: t.name })),
    }
    if (editing.value) {
      await http.put(`/admin/categories/${editing.value.id}`, payload)
    } else {
      await http.post('/admin/categories', payload)
    }
    await fetchCategories()
    showModal.value = false
  } finally {
    saving.value = false
  }
}

async function autoTranslate() {
  if (!confirm(t('products.auto_translate_confirm'))) return
  translating.value = true
  try {
    const res = await http.post('/admin/categories/auto-translate')
    const { count, langs } = res.data
    success(t('products.auto_translate_done', { count, langs }))
    const cRes = await http.get('/admin/categories')
    categories.value = cRes.data.data
  } catch {
    toastError(t('products.auto_translate_error'))
  } finally {
    translating.value = false
  }
}

async function toggleActive(cat: Category) {
  await http.patch(`/admin/categories/${cat.id}`, { active: !cat.active })
  cat.active = !cat.active
}

async function deleteCategory(id: number) {
  if (!confirm(t('categories.confirm_delete'))) return
  await http.delete(`/admin/categories/${id}`)
  await fetchCategories()
}
</script>
