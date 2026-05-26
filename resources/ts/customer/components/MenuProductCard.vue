<template>
  <div class="flex gap-3" style="padding:10px; border:1px solid #e5e0db; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08); margin-bottom:8px;">
    <!-- Sol: Kare görsel -->
    <div class="shrink-0 rounded-xl overflow-hidden" style="width:96px;height:96px;">
      <img
        v-if="product.image"
        :src="product.image"
        class="w-full h-full object-cover"
      />
      <div
        v-else
        class="w-full h-full flex items-center justify-center"
        style="background:#e8ddd4;"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color:#c4b5a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
    </div>

    <!-- Sağ: Bilgiler -->
    <div class="flex-1 min-w-0 flex flex-col justify-center">
      <p class="font-bold text-sm text-gray-900 leading-snug">{{ product.name }}</p>

      <!-- Açıklama -->
      <p
        v-if="product.description"
        class="text-xs mt-0.5 leading-snug line-clamp-2"
        style="color:#7a6e67;"
      >{{ product.description }}</p>

      <!-- Tükendi -->
      <template v-if="product.in_stock === false">
        <span class="inline-block mt-1.5 text-xs font-semibold" style="color:#dc2626; background:#fee2e2; padding:2px 8px; border-radius:99px;">Bu ürün şuan için tükendi</span>
      </template>

      <!-- Stokta: Birimler -->
      <template v-else-if="product.units && product.units.length > 0">
        <div class="flex flex-col gap-0.5 mt-1.5">
          <div v-for="(unit, i) in product.units" :key="i" class="flex items-center justify-between">
            <span class="text-sm font-bold"><span style="filter:brightness(0.7);" :style="{ color: themeColor || '#f97316' }">{{ unit.label || 'Adet' }} -</span><span :style="{ color: themeColor || '#f97316' }"> {{ formatPrice(unit.price) }}</span></span>
            <button
              v-if="ordersEnabled"
              @click="emit('add', { product, unitLabel: unit.label ?? undefined, unitPrice: unit.price })"
              class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 active:opacity-75 ml-2"
              :style="{ backgroundColor: themeColor || '#f97316' }"
            >+</button>
          </div>
        </div>
      </template>

      <!-- Stokta: Tek fiyat -->
      <template v-else>
        <div class="flex items-center justify-between mt-1.5">
          <span class="text-sm font-bold" :style="{ color: themeColor || '#f97316' }">
            {{ formatPrice(product.price) }}
          </span>
          <button
            v-if="ordersEnabled"
            @click="emit('add', { product })"
            class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 active:opacity-75 ml-2"
            :style="{ backgroundColor: themeColor || '#f97316' }"
          >+</button>
        </div>
      </template>

      <!-- Uyumluluk Etiketleri -->
      <div v-if="hasCompliance" class="flex flex-wrap items-center gap-1 mt-1.5">
        <span v-if="product.calories" class="text-xs px-1.5 py-0.5 rounded-full" style="background:#f3ede8; color:#7a6e67;">{{ product.calories }} kcal</span>
        <span v-if="product.is_vegan" class="text-xs px-1.5 py-0.5 rounded-full" style="background:#dcfce7; color:#166534;">🌱 Vegan</span>
        <span v-else-if="product.is_vegetarian" class="text-xs px-1.5 py-0.5 rounded-full" style="background:#dcfce7; color:#166534;">🥦 Vejetaryen</span>
        <span v-if="product.has_alcohol" class="text-xs px-1.5 py-0.5 rounded-full" style="background:#fee2e2; color:#991b1b;">🍺 Alkol</span>
        <span v-if="product.has_pork" class="text-xs px-1.5 py-0.5 rounded-full" style="background:#fee2e2; color:#991b1b;">🐷 Domuz</span>
        <span v-if="product.allergens && product.allergens.length" class="text-xs" style="color:#9ca3af;" :title="allergenLabels">⚠️ {{ product.allergens.map(a => ALLERGEN_ICONS[a] || a).join(' ') }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Product } from '@/types'
import { useMenuStore } from '@/customer/stores/menuStore'
import { formatPrice as _formatPrice } from '@/utils/currency'
const store = useMenuStore()

interface AddEvent { product: Product; unitLabel?: string; unitPrice?: number }

const props = defineProps<{
  product: Product
  themeColor?: string
  ordersEnabled: boolean
}>()

const emit = defineEmits<{
  (e: 'add', payload: AddEvent): void
}>()

const ALLERGEN_ICONS: Record<string, string> = {
  gluten: '🌾', milk: '🥛', egg: '🥚', peanut: '🥜',
  nuts: '🌰', soy: '🫘', sesame: '🌿', seafood: '🐟',
}
const ALLERGEN_LABELS: Record<string, string> = {
  gluten: 'Gluten', milk: 'Süt/Laktoz', egg: 'Yumurta', peanut: 'Yer Fıstığı',
  nuts: 'Kabuklu Yemiş', soy: 'Soya', sesame: 'Susam', seafood: 'Balık/Deniz Ürünleri',
}

const hasCompliance = computed(() =>
  props.product.calories ||
  props.product.is_vegan ||
  props.product.is_vegetarian ||
  props.product.has_alcohol ||
  props.product.has_pork ||
  (props.product.allergens && props.product.allergens.length > 0)
)

const allergenLabels = computed(() =>
  (props.product.allergens ?? []).map(a => ALLERGEN_LABELS[a] || a).join(', ')
)

function formatPrice(amount: number) {
  return _formatPrice(amount, store.tenant?.currency_code ?? 'TRY')
}
</script>
