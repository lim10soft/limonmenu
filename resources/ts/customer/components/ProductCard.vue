<template>
  <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex gap-3 p-3">
    <img
      v-if="product.image"
      :src="product.image"
      :alt="product.name"
      class="w-24 h-24 rounded-xl object-cover shrink-0"
    />
    <div v-else class="w-24 h-24 rounded-xl bg-gray-100 shrink-0 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
      </svg>
    </div>

    <div class="flex-1 flex flex-col justify-between min-w-0">
      <div>
        <h3 class="font-semibold text-gray-900 leading-tight">{{ product.name }}</h3>
        <p v-if="product.description" class="text-sm text-gray-500 mt-0.5 line-clamp-2">
          {{ product.description }}
        </p>
      </div>
      <div class="flex items-center justify-between mt-2">
        <span class="text-orange-500 font-bold text-base">{{ formatPrice(product.price) }}</span>

        <template v-if="store.tenant?.orders_enabled">
          <div v-if="cartQty > 0" class="flex items-center gap-2">
            <button
              @click="emit('remove')"
              class="w-7 h-7 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg leading-none"
            >−</button>
            <span class="font-bold text-gray-800 w-4 text-center">{{ cartQty }}</span>
            <button
              @click="emit('add')"
              class="w-7 h-7 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-lg leading-none"
            >+</button>
          </div>
          <button
            v-else
            @click="emit('add')"
            class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-xl leading-none shadow"
          >+</button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useMenuStore } from '../stores/menuStore'
import type { Product } from '@/types'
import { formatPrice as _formatPrice } from '@/utils/currency'

const props = defineProps<{ product: Product }>()
const emit = defineEmits<{ add: []; remove: [] }>()

const store = useMenuStore()
const cartQty = computed(() => {
  const item = store.cart.find((i) => i.product.id === props.product.id)
  return item?.quantity ?? 0
})

function formatPrice(amount: number) {
  return _formatPrice(amount, store.tenant?.currency_code ?? 'TRY')
}
</script>
