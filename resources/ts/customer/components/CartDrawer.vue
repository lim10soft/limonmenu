<template>
  <div class="fixed inset-0 z-50 flex flex-col">
    <div class="absolute inset-0 bg-black/50" @click="emit('close')"></div>

    <div class="relative mt-auto bg-white rounded-t-3xl max-h-[85vh] flex flex-col shadow-2xl">
      <div class="flex justify-center pt-3 pb-1">
        <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
      </div>

      <div class="px-4 pb-2 flex items-center justify-between">
        <h2 class="text-lg font-bold">{{ t('cart.title') }}</h2>
        <button @click="emit('close')" class="text-gray-400 p-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto px-4 pb-2 space-y-3">
        <div v-for="item in store.cart" :key="`${item.product.id}-${item.note}`" class="flex items-center gap-3">
          <div class="flex-1 min-w-0">
            <p class="font-medium text-gray-900 truncate">{{ item.product.name }}</p>
            <p v-if="item.note" class="text-xs text-gray-400 truncate">{{ item.note }}</p>
            <p class="text-sm text-orange-500 font-semibold mt-0.5">
              {{ formatPrice(item.product.price * item.quantity) }}
            </p>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button
              @click="store.updateQuantity(item.product.id, item.note, item.quantity - 1)"
              class="w-7 h-7 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold"
            >−</button>
            <span class="w-5 text-center font-bold">{{ item.quantity }}</span>
            <button
              @click="store.updateQuantity(item.product.id, item.note, item.quantity + 1)"
              class="w-7 h-7 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold"
            >+</button>
          </div>
        </div>
      </div>

      <div class="px-4 py-2">
        <textarea
          v-model="orderNote"
          rows="2"
          :placeholder="t('cart.note')"
          class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
        ></textarea>
      </div>

      <div class="px-4 pb-6 pt-2 border-t">
        <div class="flex justify-between text-sm text-gray-500 mb-3">
          <span>{{ t('cart.total') }}</span>
          <span class="text-lg font-bold text-gray-900">{{ formatPrice(store.cartTotal) }}</span>
        </div>
        <button
          @click="submitOrder"
          :disabled="submitting"
          class="w-full py-3.5 rounded-xl text-white font-bold text-base disabled:opacity-60 transition-opacity"
          :style="{ backgroundColor: store.tenant?.theme_color || '#f97316' }"
        >
          {{ submitting ? t('cart.submitting') : t('cart.submit') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMenuStore } from '../stores/menuStore'
import { formatPrice as _formatPrice } from '@/utils/currency'

const { t } = useI18n()
const emit = defineEmits<{ close: []; ordered: [orderId: number] }>()
const store = useMenuStore()
const orderNote = ref('')
const submitting = ref(false)

async function submitOrder() {
  submitting.value = true
  try {
    const order = await store.submitOrder(orderNote.value)
    emit('ordered', order.id)
  } catch {
    alert(t('cart.error'))
  } finally {
    submitting.value = false
  }
}

function formatPrice(amount: number) {
  return _formatPrice(amount, store.tenant?.currency_code ?? 'TRY')
}
</script>
