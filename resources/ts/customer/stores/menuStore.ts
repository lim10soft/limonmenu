import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Tenant, QrTable, Category, CartItem, Product } from '@/types'
import http from '@/api/http'
import { setLanguage } from '@/i18n'

export const useMenuStore = defineStore('menu', () => {
  const tenant = ref<Tenant | null>(null)
  const table = ref<QrTable | null>(null)
  const department = ref<{ id: number; name: string; display_name: string | null; logo: string | null; banner_image: string | null } | null>(null)
  const categories = ref<Category[]>([])
  const featuredProducts = ref<Product[]>([])
  const cart = ref<CartItem[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const activeLanguages = ref<string[]>([])

  const currentSlug = ref('')
  const currentToken = ref('')
  const currentDeptToken = ref('')
  const currentLang = ref('')

  const cartTotal = computed(() =>
    cart.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0)
  )

  const cartCount = computed(() =>
    cart.value.reduce((sum, item) => sum + item.quantity, 0)
  )

  async function loadMenu(slug: string, tableToken?: string, lang?: string, deptToken?: string) {
    currentSlug.value = slug
    currentToken.value = tableToken ?? ''
    currentDeptToken.value = deptToken ?? ''
    loading.value = true
    error.value = null
    try {
      // Explicit lang (from URL ?lang= or user selection) takes priority.
      // Fall back to localStorage only if user previously made an explicit choice.
      // If neither, send no lang param so the server defaults to tenant primary_lang.
      const langParam = lang ?? localStorage.getItem('qr_lang') ?? null
      const langQuery = langParam ? `?lang=${langParam}` : ''
      let url: string
      if (deptToken) {
        url = `/menu/${slug}/dept/${deptToken}${langQuery}`
      } else if (tableToken) {
        url = `/menu/${slug}/${tableToken}${langQuery}`
      } else {
        url = `/menu/${slug}${langQuery}`
      }
      const res = await http.get(url)
      tenant.value = res.data.tenant
      table.value = res.data.table
      department.value = res.data.department
      categories.value = res.data.categories
      featuredProducts.value = res.data.featured_products ?? []
      activeLanguages.value = res.data.active_languages ?? []
      if (res.data.lang) {
        currentLang.value = res.data.lang
        setLanguage(res.data.lang)
      }
    } catch {
      error.value = 'Menü yüklenemedi.'
    } finally {
      loading.value = false
    }
  }

  async function reloadWithLang(lang: string) {
    if (currentSlug.value) {
      await loadMenu(currentSlug.value, currentToken.value || undefined, lang, currentDeptToken.value || undefined)
    }
  }

  function addToCart(product: Product, quantity = 1, note = '') {
    const existing = cart.value.find((i) => i.product.id === product.id && i.note === note)
    if (existing) {
      existing.quantity += quantity
    } else {
      cart.value.push({ product, quantity, note })
    }
  }

  function removeFromCart(productId: number, note = '') {
    const idx = cart.value.findIndex((i) => i.product.id === productId && i.note === note)
    if (idx !== -1) cart.value.splice(idx, 1)
  }

  function updateQuantity(productId: number, note: string, quantity: number) {
    const item = cart.value.find((i) => i.product.id === productId && i.note === note)
    if (!item) return
    if (quantity <= 0) {
      removeFromCart(productId, note)
    } else {
      item.quantity = quantity
    }
  }

  function clearCart() {
    cart.value = []
  }

  async function submitOrder(orderNote = '') {
    if (!tenant.value) throw new Error('Restoran bilgisi eksik.')
    const payload: Record<string, unknown> = {
      note: orderNote,
      items: cart.value.map((i) => ({
        product_id: i.product.id,
        quantity: i.quantity,
        note: i.note,
      })),
    }
    if (tenant.value.tables_enabled && table.value) {
      payload.table_token = table.value.token
    }
    const res = await http.post(`/menu/${tenant.value.slug}/order`, payload)
    clearCart()
    return res.data.order
  }

  return {
    tenant, table, department, categories, featuredProducts, cart, loading, error,
    activeLanguages, currentLang,
    cartTotal, cartCount,
    loadMenu, reloadWithLang, addToCart, removeFromCart, updateQuantity, clearCart, submitOrder,
  }
})
