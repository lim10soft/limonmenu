import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { TenantUser, Tenant } from '@/types'
import http from '@/api/http'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(
    localStorage.getItem('auth_token') ?? sessionStorage.getItem('auth_token')
  )
  const user = ref<TenantUser | null>(null)
  const tenant = ref<Tenant | null>(null)
  const appUrl = ref<string>(window.location.origin)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'superadmin')

  async function login(email: string, password: string, remember = false) {
    const res = await http.post('/admin/login', { email, password })
    _setSession(res.data, remember)
  }

  async function register(restaurantName: string, email: string, password: string, passwordConfirmation: string) {
    const res = await http.post('/admin/register', {
      restaurant_name: restaurantName,
      email,
      password,
      password_confirmation: passwordConfirmation,
    })
    _setSession(res.data, false)
  }

  function _setSession(data: { token: string; user: TenantUser; tenant: Tenant; app_url?: string }, remember: boolean) {
    token.value = data.token
    user.value = data.user
    tenant.value = data.tenant
    if (data.app_url) appUrl.value = data.app_url
    if (remember) {
      localStorage.setItem('auth_token', data.token)
      sessionStorage.removeItem('auth_token')
    } else {
      sessionStorage.setItem('auth_token', data.token)
      localStorage.removeItem('auth_token')
    }
  }

  async function fetchMe() {
    if (!token.value) return
    const res = await http.get('/admin/me')
    user.value = res.data.user
    tenant.value = res.data.tenant
    if (res.data.app_url) appUrl.value = res.data.app_url
  }

  function logout() {
    token.value = null
    user.value = null
    tenant.value = null
    localStorage.removeItem('auth_token')
    sessionStorage.removeItem('auth_token')
  }

  return { token, user, tenant, appUrl, isAuthenticated, isAdmin, login, register, fetchMe, logout }
})
