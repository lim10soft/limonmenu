<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ t('orders.title') }}</h1>

    <div class="flex gap-2 mb-4 flex-wrap">
      <button
        v-for="s in statusOptions"
        :key="s"
        @click="activeStatus = s"
        class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors"
        :class="activeStatus === s ? 'bg-orange-500 text-white' : 'bg-white text-gray-600 border border-gray-200'"
      >{{ t(`orders.status.${s}`) }}</button>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="animate-spin h-8 w-8 border-4 border-orange-500 border-t-transparent rounded-full"></div>
    </div>

    <div v-else-if="orders.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400">
      {{ t('orders.no_orders') }}
    </div>

    <div v-else class="space-y-3">
      <div v-for="order in orders" :key="order.id" class="bg-white rounded-2xl shadow-sm p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div>
            <p class="font-semibold text-gray-800">{{ order.table?.name ?? '—' }} — #{{ order.id }}</p>
            <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
          </div>
          <div class="text-right">
            <p class="font-bold text-gray-900">{{ formatPrice(order.total) }}</p>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="statusClass(order.status)">
              {{ t(`orders.status.${order.status}`) }}
            </span>
          </div>
        </div>

        <div class="border-t pt-3 space-y-1">
          <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm text-gray-600">
            <span>{{ item.quantity }}x {{ item.product?.name }}</span>
            <span>{{ formatPrice(item.unit_price * item.quantity) }}</span>
          </div>
        </div>

        <div v-if="order.note" class="mt-2 text-sm text-gray-400 italic">{{ t('orders.note_prefix') }}{{ order.note }}</div>

        <div class="flex gap-2 mt-3 flex-wrap">
          <button
            v-for="s in nextStatuses(order.status)"
            :key="s"
            @click="updateStatus(order, s)"
            class="px-3 py-1.5 bg-orange-50 text-orange-600 rounded-xl text-sm font-medium hover:bg-orange-100 transition-colors"
          >{{ t(`orders.status.${s}`) }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Order } from '@/types'
import http from '@/api/http'

const { t } = useI18n()
const orders = ref<Order[]>([])
const loading = ref(true)
const activeStatus = ref('all')

const statusOptions = ['all', 'pending', 'preparing', 'ready', 'delivered']

onMounted(fetchOrders)
watch(activeStatus, fetchOrders)

async function fetchOrders() {
  loading.value = true
  try {
    const params = activeStatus.value !== 'all' ? { status: activeStatus.value } : {}
    const res = await http.get('/admin/orders', { params })
    orders.value = res.data.data
  } finally {
    loading.value = false
  }
}

async function updateStatus(order: Order, status: string) {
  await http.patch(`/admin/orders/${order.id}`, { status })
  order.status = status as Order['status']
}

function nextStatuses(current: string): string[] {
  const flow: Record<string, string[]> = {
    pending: ['preparing', 'cancelled'], preparing: ['ready', 'cancelled'],
    ready: ['delivered'], delivered: [], cancelled: [],
  }
  return flow[current] ?? []
}

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
