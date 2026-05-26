<template>
  <div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ t('tenants.title') }}</h1>

    <!-- Git Pull -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-emerald-100 mb-6 flex items-center gap-4 flex-wrap">
      <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-gray-800">{{ t('tenants.git_pull_title') }}</p>
        <p class="text-xs text-gray-400">{{ t('tenants.git_pull_desc') }}</p>
      </div>
      <div class="flex items-center gap-3 shrink-0">
        <button @click="gitPull" :disabled="pulling"
          class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold disabled:opacity-60 transition-colors">
          <svg class="w-4 h-4" :class="pulling ? 'animate-spin' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
          {{ pulling ? t('tenants.git_pulling') : t('tenants.git_pull_btn') }}
        </button>
        <span v-if="pullResult" class="text-xs font-medium" :class="pullOk ? 'text-emerald-600' : 'text-red-500'">
          {{ pullOk ? '✓' : '✗' }} {{ pullResult }}
        </span>
      </div>
    </div>

    <div v-if="loading" class="text-sm text-gray-400 py-8 text-center">{{ t('common.loading') }}</div>

    <div v-else class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
            <th class="px-4 py-3 text-left w-12">{{ t('tenants.id') }}</th>
            <th class="px-4 py-3 text-left">{{ t('tenants.name') }}</th>
            <th class="px-4 py-3 text-left hidden md:table-cell">{{ t('tenants.slug') }}</th>
            <th class="px-4 py-3 text-left">{{ t('tenants.plan') }}</th>
            <th class="px-4 py-3 text-center">{{ t('tenants.active') }}</th>
            <th class="px-4 py-3 text-center hidden sm:table-cell">{{ t('tenants.users') }}</th>
            <th class="px-4 py-3 text-left hidden lg:table-cell">{{ t('tenants.created') }}</th>
            <th class="px-4 py-3 text-right">{{ t('tenants.actions') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="tenant in tenants" :key="tenant.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-gray-400 font-mono">{{ tenant.id }}</td>
            <td class="px-4 py-3 font-medium text-gray-900">{{ tenant.name }}</td>
            <td class="px-4 py-3 text-gray-400 font-mono text-xs hidden md:table-cell">{{ tenant.slug }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                :class="planClass(tenant.plan)">
                {{ planLabel(tenant.plan) }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <button @click="toggleActive(tenant)"
                class="relative w-9 h-5 rounded-full transition-colors shrink-0"
                :class="tenant.active ? 'bg-orange-500' : 'bg-gray-200'">
                <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform"
                  :class="tenant.active ? 'translate-x-4' : 'translate-x-0.5'"></span>
              </button>
            </td>
            <td class="px-4 py-3 text-center text-gray-500 hidden sm:table-cell">{{ (tenant as any).users_count ?? 0 }}</td>
            <td class="px-4 py-3 text-gray-400 text-xs hidden lg:table-cell">{{ formatDate(tenant.created_at) }}</td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <button @click="openEdit(tenant)" class="p-1.5 rounded-lg text-gray-400 hover:text-orange-500 hover:bg-orange-50 transition-colors" title="Düzenle">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                </button>
                <button @click="confirmDelete(tenant)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Sil">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Edit Modal -->
    <Teleport to="body">
      <Transition name="lm-overlay">
        <div v-if="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="editModal = false">
          <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-5">{{ t('tenants.edit_title') }}</h2>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('tenants.name') }}</label>
                <input v-model="editForm.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('tenants.slug') }}</label>
                <input v-model="editForm.slug" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-300" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('tenants.plan') }}</label>
                <select v-model="editForm.plan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                  <option value="free">{{ t('tenants.plan_free') }}</option>
                  <option value="starter">{{ t('tenants.plan_starter') }}</option>
                  <option value="pro">{{ t('tenants.plan_pro') }}</option>
                </select>
              </div>
              <label class="flex items-center justify-between gap-4 cursor-pointer">
                <span class="text-sm font-medium text-gray-700">{{ t('tenants.active') }}</span>
                <button @click="editForm.active = !editForm.active"
                  class="relative w-11 h-6 rounded-full transition-colors shrink-0"
                  :class="editForm.active ? 'bg-orange-500' : 'bg-gray-200'">
                  <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform"
                    :class="editForm.active ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
              </label>
            </div>
            <div class="flex gap-2 mt-6">
              <button @click="saveEdit" :disabled="saving"
                class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 disabled:opacity-60 transition-colors">
                {{ saving ? t('common.saving') : t('common.save') }}
              </button>
              <button @click="editModal = false" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                {{ t('common.cancel') }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <Transition name="lm-overlay">
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="deleteTarget = null">
          <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
            </div>
            <p class="text-sm text-gray-700 text-center whitespace-pre-line mb-6">{{ t('tenants.confirm_delete', { name: deleteTarget.name }) }}</p>
            <div class="flex gap-2">
              <button @click="doDelete" :disabled="deleting"
                class="flex-1 py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 disabled:opacity-60 transition-colors">
                {{ deleting ? t('common.loading') : t('common.delete') }}
              </button>
              <button @click="deleteTarget = null" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                {{ t('common.cancel') }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import http from '@/api/http'
import { useToast } from '../composables/useToast'
import type { Tenant } from '@/types'

const { t } = useI18n()
const { success, error: toastError } = useToast()

type TenantRow = Tenant & { users_count: number; created_at: string }

const loading = ref(true)
const tenants = ref<TenantRow[]>([])
const saving = ref(false)
const deleting = ref(false)
const pulling = ref(false)
const pullResult = ref('')
const pullOk = ref(false)

async function gitPull() {
  pulling.value = true
  pullResult.value = ''
  try {
    const res = await http.post('/admin/git/pull')
    pullOk.value = true
    pullResult.value = res.data.message ?? 'Tamamlandı.'
  } catch (e: any) {
    pullOk.value = false
    pullResult.value = e?.response?.data?.message ?? 'Hata oluştu.'
  } finally {
    pulling.value = false
  }
}

const editModal = ref(false)
const editTarget = ref<TenantRow | null>(null)
const editForm = ref({ name: '', slug: '', plan: 'free' as 'free'|'starter'|'pro', active: true })

const deleteTarget = ref<TenantRow | null>(null)

onMounted(async () => {
  try {
    const res = await http.get('/admin/tenants')
    tenants.value = res.data
  } finally {
    loading.value = false
  }
})

function planLabel(plan: string) {
  const map: Record<string, string> = { free: t('tenants.plan_free'), starter: t('tenants.plan_starter'), pro: t('tenants.plan_pro') }
  return map[plan] ?? plan
}

function planClass(plan: string) {
  if (plan === 'pro') return 'bg-purple-100 text-purple-700'
  if (plan === 'starter') return 'bg-blue-100 text-blue-700'
  return 'bg-gray-100 text-gray-600'
}

function formatDate(dt: string) {
  return new Date(dt).toLocaleDateString()
}

async function toggleActive(tenant: TenantRow) {
  const newVal = !tenant.active
  try {
    await http.patch(`/admin/tenants/${tenant.id}`, { active: newVal })
    tenant.active = newVal
  } catch {
    toastError(t('common.error'))
  }
}

function openEdit(tenant: TenantRow) {
  editTarget.value = tenant
  editForm.value = { name: tenant.name, slug: tenant.slug, plan: tenant.plan as any, active: tenant.active }
  editModal.value = true
}

async function saveEdit() {
  if (!editTarget.value) return
  saving.value = true
  try {
    const res = await http.patch(`/admin/tenants/${editTarget.value.id}`, editForm.value)
    const idx = tenants.value.findIndex(t => t.id === editTarget.value!.id)
    if (idx !== -1) tenants.value[idx] = { ...tenants.value[idx], ...res.data }
    editModal.value = false
    success(t('tenants.saved'))
  } catch {
    toastError(t('common.error'))
  } finally {
    saving.value = false
  }
}

function confirmDelete(tenant: TenantRow) {
  deleteTarget.value = tenant
}

async function doDelete() {
  if (!deleteTarget.value) return
  deleting.value = true
  try {
    await http.delete(`/admin/tenants/${deleteTarget.value.id}`)
    tenants.value = tenants.value.filter(t => t.id !== deleteTarget.value!.id)
    deleteTarget.value = null
    success(t('tenants.deleted'))
  } catch {
    toastError(t('common.error'))
  } finally {
    deleting.value = false
  }
}
</script>

<style scoped>
.lm-overlay-enter-active, .lm-overlay-leave-active { transition: opacity .2s; }
.lm-overlay-enter-from, .lm-overlay-leave-to { opacity: 0; }
</style>
