<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ t('settings.title') }}</h1>

    <div class="space-y-6">
      <!-- Genel -->
      <div v-if="!auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">{{ t('settings.general_title') }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.restaurant_name') }}</label>
            <input v-model="form.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.slug_label') }}</label>
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-400">{{ t('settings.slug_prefix') }}</span>
              <input v-model="form.slug" type="text" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.logo_url') }}</label>
            <ImageUploader v-model="form.logo" type="tenants" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.banner_image_label') }}</label>
            <ImageUploader v-model="form.banner_image" type="tenants" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.instagram_url_label') }}</label>
            <input v-model="form.instagram_url" type="url" placeholder="https://instagram.com/..." class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Para Birimi</label>
            <select v-model="form.currency_code" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
              <option v-for="[code, curr] in currencyEntries" :key="code" :value="code">{{ curr.label }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.theme_color') }}</label>
            <div class="flex items-center gap-3">
              <input v-model="form.theme_color" type="color" class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer" />
              <input v-model="form.theme_color" type="text" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
            </div>
          </div>
        </div>
      </div>

      <!-- Dil Yönetimi -->
      <div v-if="!auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-1">{{ t('settings.lang_title') }}</h2>
        <p class="text-sm text-gray-400 mb-4">{{ t('settings.lang_desc') }}</p>
        <div v-if="langLoading" class="text-sm text-gray-400 py-2">{{ t('common.loading') }}</div>
        <div v-else class="divide-y divide-gray-50">
          <div v-for="lang in languages" :key="lang.lang_code" class="flex items-center gap-3 py-3">
            <span class="text-xl leading-none w-7 shrink-0">{{ getLangMeta(lang.lang_code)?.flag }}</span>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-800 leading-tight">{{ getLangMeta(lang.lang_code)?.label }}</p>
              <p class="text-xs text-gray-400 uppercase">{{ lang.lang_code }}</p>
            </div>
            <div class="shrink-0">
              <span v-if="lang.lang_code === primaryLang" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                {{ t('settings.primary_badge') }}
              </span>
              <button v-else @click="primaryLang = lang.lang_code" class="text-xs text-gray-400 hover:text-amber-600 hover:bg-amber-50 px-2 py-0.5 rounded-full transition-colors">
                {{ t('settings.set_primary') }}
              </button>
            </div>
            <button
              @click="toggleLang(lang)"
              :disabled="lang.active && activeCount === 1"
              class="relative w-11 h-6 rounded-full transition-colors shrink-0 disabled:opacity-40"
              :class="lang.active ? 'bg-orange-500' : 'bg-gray-200'"
              :title="lang.active && activeCount === 1 ? t('settings.at_least_one_lang') : ''"
            >
              <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="lang.active ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
          </div>
        </div>
        <button @click="saveLangs" :disabled="savingLangs" class="mt-4 w-full py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 disabled:opacity-60 transition-colors">
          {{ savingLangs ? t('settings.saving_langs') : t('settings.save_langs') }}
        </button>
      </div>

      <!-- Özellik Ayarları -->
      <div v-if="!auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">{{ t('settings.features_title') }}</h2>
        <div class="space-y-4">
          <label class="flex items-center justify-between gap-4 cursor-pointer">
            <div>
              <p class="text-sm font-medium text-gray-800">{{ t('settings.tables_enabled_label') }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ t('settings.tables_enabled_desc') }}</p>
            </div>
            <button
              @click="form.tables_enabled = !form.tables_enabled"
              class="relative w-11 h-6 rounded-full transition-colors shrink-0"
              :class="form.tables_enabled ? 'bg-orange-500' : 'bg-gray-200'"
            >
              <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="form.tables_enabled ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
          </label>
          <label class="flex items-center justify-between gap-4 cursor-pointer">
            <div>
              <p class="text-sm font-medium text-gray-800">{{ t('settings.orders_enabled_label') }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ t('settings.orders_enabled_desc') }}</p>
            </div>
            <button
              @click="form.orders_enabled = !form.orders_enabled"
              class="relative w-11 h-6 rounded-full transition-colors shrink-0"
              :class="form.orders_enabled ? 'bg-orange-500' : 'bg-gray-200'"
            >
              <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="form.orders_enabled ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
          </label>
        </div>
      </div>

      <!-- API Token -->
      <div v-if="!auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5 border border-orange-100">
        <div class="flex items-start gap-3 mb-3">
          <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z"/></svg>
          </div>
          <div>
            <h2 class="font-semibold text-gray-800">API Entegrasyon Token</h2>
            <p class="text-sm text-gray-400 mt-0.5">NexoPOS QR Yönetimi → Dijital Menü Ayarları ekranına yapıştırın.</p>
          </div>
        </div>
        <div class="flex items-center gap-2 mt-3">
          <code class="flex-1 block bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-700 font-mono break-all select-all">
            {{ tokenVisible ? auth.token : maskedToken }}
          </code>
          <button @click="tokenVisible = !tokenVisible" class="shrink-0 p-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors text-gray-500" :title="tokenVisible ? 'Gizle' : 'Göster'">
            <svg v-if="!tokenVisible" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
          </button>
          <button @click="copyToken" class="shrink-0 flex items-center gap-1.5 px-3 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-semibold transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.375"/></svg>
            {{ copied ? 'Kopyalandı!' : 'Kopyala' }}
          </button>
        </div>
      </div>

      <!-- Şifre -->
      <div v-if="!auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">{{ t('settings.password_title') }}</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.current_password') }}</label>
            <input v-model="passwords.current" type="password" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('settings.new_password') }}</label>
            <input v-model="passwords.new" type="password" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
        </div>
        <button @click="changePassword" class="mt-4 px-4 py-2 bg-gray-800 text-white rounded-xl text-sm font-medium hover:bg-gray-700 transition-colors">
          {{ t('settings.update_password') }}
        </button>
      </div>

      <!-- Git Ayarları — sadece superadmin -->
      <div v-if="auth.isAdmin" class="bg-white rounded-2xl shadow-sm p-5 border border-emerald-100 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
          </div>
          <div>
            <h2 class="font-semibold text-gray-800">Git Ayarları</h2>
            <p class="text-xs text-gray-400 mt-0.5">Sunucu güncelleme (git pull) yapılandırması.</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Git Yolu</label>
          <input v-model="gitConfig.git_path" type="text" placeholder="git  veya  C:\Program Files\Git\bin\git.exe  veya  /usr/bin/git"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-300" />
          <p class="text-xs text-gray-400 mt-1">Boş bırakılırsa <code class="bg-gray-100 px-1 rounded">git</code> komutu kullanılır.</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
          <input v-model="gitConfig.branch" type="text" placeholder="main"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-300" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Repo URL <span class="text-gray-400 font-normal">(token dahil)</span></label>
          <input v-model="gitConfig.repo_url" type="text" placeholder="https://TOKEN@github.com/kullanici/repo.git"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-300" />
          <p class="text-xs text-gray-400 mt-1">
            GitHub → Settings → Developer settings → Personal access tokens → token oluştur, URL'ye ekle:<br>
            <code class="bg-gray-100 px-1 rounded text-xs">https://ghp_TOKENBURAYA@github.com/kullanici/repo.git</code>
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-1">
          <button @click="saveGitConfig" :disabled="savingGit"
            class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl text-sm font-semibold disabled:opacity-60 transition-colors">
            {{ savingGit ? 'Kaydediliyor...' : 'Ayarları Kaydet' }}
          </button>
          <button @click="gitPull" :disabled="pulling"
            class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold disabled:opacity-60 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            {{ pulling ? 'Çekiliyor...' : 'Git Pull' }}
          </button>
          <button @click="runMigrate" :disabled="migrating"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold disabled:opacity-60 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg>
            {{ migrating ? 'Çalışıyor...' : 'Migrate Çalıştır' }}
          </button>
        </div>
        <pre v-if="pullOutput" class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-700 whitespace-pre-wrap max-h-48 overflow-y-auto">{{ pullOutput }}</pre>
        <pre v-if="migrateOutput" class="bg-indigo-50 border border-indigo-200 rounded-xl p-3 text-xs text-indigo-800 whitespace-pre-wrap max-h-48 overflow-y-auto">{{ migrateOutput }}</pre>
      </div>

      <button v-if="!auth.isAdmin" @click="saveSettings" :disabled="saving" class="w-full py-3 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 disabled:opacity-60 transition-colors">
        {{ saving ? t('settings.saving') : t('settings.save') }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../stores/authStore'
import { ADMIN_LANGUAGES } from '../i18n'
import type { TenantLanguage } from '@/types'
import http from '@/api/http'
import { useToast } from '../composables/useToast'
import ImageUploader from '../components/ImageUploader.vue'
import { CURRENCIES } from '@/utils/currency'

const currencyEntries = Object.entries(CURRENCIES)

const { t } = useI18n()
const { success, error: toastError } = useToast()
const auth = useAuthStore()
const saving = ref(false)
const langLoading = ref(true)
const savingLangs = ref(false)
const tokenVisible = ref(false)
const copied = ref(false)
const pulling = ref(false)
const pullOutput = ref('')
const deploySecret = ref('')
const savingGit = ref(false)
const gitConfig = ref({ git_path: '', branch: 'main', repo_url: '' })
const migrating = ref(false)
const migrateOutput = ref('')

const maskedToken = computed(() => {
  if (!auth.token) return '—'
  return auth.token.slice(0, 6) + '•'.repeat(20) + auth.token.slice(-4)
})

function copyToken() {
  if (!auth.token) return
  navigator.clipboard.writeText(auth.token)
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}
const languages = ref<TenantLanguage[]>([])
const primaryLang = ref('tr')

const activeCount = computed(() => languages.value.filter((l) => l.active).length)

const form = ref({ name: '', slug: '', logo: '', theme_color: '#f97316', tables_enabled: false, orders_enabled: false, currency_code: 'TRY', banner_image: '', instagram_url: '' })
const passwords = ref({ current: '', new: '' })

function getLangMeta(code: string) {
  return ADMIN_LANGUAGES.find((l) => l.code === code)
}

function toggleLang(lang: TenantLanguage) {
  if (lang.active && activeCount.value === 1) return
  lang.active = !lang.active
}

watch(() => auth.tenant, (tenant) => {
  if (tenant) {
    form.value = {
      name: tenant.name, slug: tenant.slug, logo: tenant.logo ?? '',
      theme_color: tenant.theme_color,
      tables_enabled: tenant.tables_enabled ?? false,
      orders_enabled: tenant.orders_enabled ?? false,
      currency_code: tenant.currency_code ?? 'TRY',
      banner_image: tenant.banner_image ?? '',
      instagram_url: tenant.instagram_url ?? '',
    }
    primaryLang.value = tenant.primary_lang ?? 'tr'
  }
}, { immediate: true })

watch(() => auth.isAdmin, async (isAdmin) => {
  if (isAdmin) {
    try {
      const res = await http.get('/admin/git/config')
      gitConfig.value = { git_path: res.data.git_path ?? '', branch: res.data.branch ?? 'main', repo_url: res.data.repo_url ?? '' }
    } catch { /* ignore */ }
    langLoading.value = false
  }
}, { immediate: true })

onMounted(async () => {
  if (auth.isAdmin) return
  try {
    const res = await http.get('/admin/languages')
    languages.value = res.data.data
    primaryLang.value = res.data.primary_lang ?? 'tr'
  } finally {
    langLoading.value = false
  }
})

async function saveLangs() {
  savingLangs.value = true
  try {
    await http.put('/admin/languages', {
      primary_lang: primaryLang.value,
      languages: languages.value.map((l, i) => ({ lang_code: l.lang_code, active: l.active, sort_order: i })),
    })
    await auth.fetchMe()
    success(t('settings.saved_langs'))
  } catch {
    toastError(t('settings.error_langs'))
  } finally {
    savingLangs.value = false
  }
}

async function saveSettings() {
  saving.value = true
  try {
    await http.put('/admin/settings', { ...form.value, primary_lang: primaryLang.value })
    await auth.fetchMe()
    success(t('settings.saved_settings'))
  } catch {
    toastError(t('settings.error_settings'))
  } finally {
    saving.value = false
  }
}


async function saveGitConfig() {
  savingGit.value = true
  try {
    await http.post('/admin/git/config', gitConfig.value)
    success('Git ayarları kaydedildi.')
  } catch {
    toastError('Kayıt sırasında hata oluştu.')
  } finally {
    savingGit.value = false
  }
}

async function gitPull() {
  if (!auth.isAdmin && !deploySecret.value) {
    toastError('Deploy Secret giriniz.')
    return
  }
  pulling.value = true
  pullOutput.value = ''
  try {
    const headers: Record<string, string> = {}
    if (!auth.isAdmin) headers['X-Deploy-Secret'] = deploySecret.value
    const res = await http.post('/admin/git/pull', {}, { headers })
    pullOutput.value = res.data.output || res.data.message
    success('Git pull tamamlandı.')
  } catch (e: any) {
    pullOutput.value = e?.response?.data?.message || 'Hata oluştu.'
    toastError(pullOutput.value)
  } finally {
    pulling.value = false
  }
}

async function runMigrate() {
  migrating.value = true
  migrateOutput.value = ''
  try {
    const res = await http.post('/admin/git/migrate')
    migrateOutput.value = res.data.output || ''
    if (res.data.success) success('Migrate tamamlandı.')
    else toastError('Migrate hatası.')
  } catch (e: any) {
    migrateOutput.value = e?.response?.data?.message || 'Hata oluştu.'
    toastError(migrateOutput.value)
  } finally {
    migrating.value = false
  }
}

async function changePassword() {
  try {
    await http.post('/admin/change-password', passwords.value)
    passwords.value = { current: '', new: '' }
    success(t('settings.saved_password'))
  } catch {
    toastError(t('settings.error_password'))
  }
}
</script>
