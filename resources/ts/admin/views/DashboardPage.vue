<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ t('dashboard.title') }}</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <StatCard :icon="ICONS.orders"   :label="t('dashboard.today_orders')"   :value="stats.todayOrders"               color="orange" />
      <StatCard :icon="ICONS.revenue"  :label="t('dashboard.today_revenue')"  :value="formatPrice(stats.todayRevenue)"  color="green"  />
      <StatCard :icon="ICONS.products" :label="t('dashboard.total_products')" :value="stats.totalProducts"              color="blue"   />
      <StatCard :icon="ICONS.tables"   :label="t('dashboard.active_tables')"  :value="stats.activeTables"              color="purple" />
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-gray-800">{{ t('dashboard.recent_orders') }}</h2>
        <router-link to="/siparisler" class="text-sm text-orange-500 hover:underline">{{ t('dashboard.view_all') }}</router-link>
      </div>
      <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-400">
        {{ t('dashboard.no_orders') }}
      </div>
      <div v-else class="space-y-3">
        <div
          v-for="order in recentOrders"
          :key="order.id"
          class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0"
        >
          <div>
            <p class="font-medium text-gray-800 text-sm">{{ order.table?.name ?? t('common.note') }}</p>
            <p class="text-xs text-gray-400">{{ formatDate(order.created_at) }}</p>
          </div>
          <div class="text-right">
            <p class="font-semibold text-gray-900 text-sm">{{ formatPrice(order.total) }}</p>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="statusClass(order.status)">
              {{ t(`orders.status.${order.status}`) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Order } from '@/types'
import http from '@/api/http'
import StatCard from '../components/StatCard.vue'

const { t } = useI18n()

const ICONS = {
  orders:   `<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6"/><path d="M9 16h6"/>`,
  revenue:  `<circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/>`,
  products: `<path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/>`,
  tables:   `<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/>`,
}

const stats = ref({ todayOrders: 0, todayRevenue: 0, totalProducts: 0, activeTables: 0 })
const recentOrders = ref<Order[]>([])

onMounted(async () => {
  try {
    const res = await http.get('/admin/dashboard')
    stats.value = res.data.stats
    recentOrders.value = res.data.recent_orders
  } catch {}
})

function formatPrice(v: number) {
  return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(v)
}

function formatDate(d: string) {
  return new Intl.DateTimeFormat('tr-TR', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit' }).format(new Date(d))
}

function statusClass(s: string) {
  const map: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-700', preparing: 'bg-blue-100 text-blue-700',
    ready: 'bg-green-100 text-green-700', delivered: 'bg-gray-100 text-gray-500', cancelled: 'bg-red-100 text-red-600',
  }
  return map[s] ?? 'bg-gray-100 text-gray-500'
}
</script>
