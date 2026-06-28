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

      <!-- Uyumluluk Bölümü -->
      <div v-if="hasCompliance" style="margin-top:6px;">

        <!-- Satır 1: Kalori + Diyet Etiketleri -->
        <div v-if="hasDietary" style="display:flex; flex-wrap:wrap; gap:4px; margin-bottom:4px;">
          <span v-if="product.calories" style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:#f0ece8; color:#7a6e67; letter-spacing:0.01em;">
            🔥 {{ product.calories }} kcal
          </span>
          <span v-if="product.is_vegan" style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:#dcfce7; color:#15803d;">
            🌱 Vegan
          </span>
          <span v-else-if="product.is_vegetarian" style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:#dcfce7; color:#15803d;">
            🥦 Vejetaryen
          </span>
          <span v-if="product.has_alcohol" style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:#fee2e2; color:#b91c1c;">
            🍺 Alkol
          </span>
          <span v-if="product.has_pork" style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:99px; background:#fee2e2; color:#b91c1c;">
            🐷 Domuz
          </span>
        </div>

        <!-- Satır 2: Alerjenler -->
        <div v-if="product.allergens && product.allergens.length" style="display:flex; flex-wrap:wrap; align-items:center; gap:3px;">
          <span style="font-size:9px; font-weight:700; color:#b45309; text-transform:uppercase; letter-spacing:0.04em; margin-right:1px;">⚠ İçerir:</span>
          <span
            v-for="a in product.allergens"
            :key="a"
            style="font-size:10px; font-weight:600; padding:1px 6px; border-radius:4px; background:#fef3c7; color:#92400e; border:1px solid #fde68a;"
          >{{ ALLERGEN_LABELS[a] || a }}</span>
        </div>

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

const ALLERGEN_LABELS: Record<string, string> = {
  gluten: 'Gluten', shellfish: 'Kabuklu Deniz', egg: 'Yumurta', fish: 'Balık',
  peanut: 'Yer Fıstığı', soy: 'Soya', milk: 'Süt', nuts: 'Kabuklu Yemiş',
  celery: 'Kereviz', mustard: 'Hardal', sesame: 'Susam', sulphites: 'Sülfitler',
  lupin: 'Acı Bakla', molluscs: 'Yumuşakça',
}

const hasDietary = computed(() =>
  props.product.calories ||
  props.product.is_vegan ||
  props.product.is_vegetarian ||
  props.product.has_alcohol ||
  props.product.has_pork
)

const hasCompliance = computed(() =>
  hasDietary.value || (props.product.allergens && props.product.allergens.length > 0)
)

function formatPrice(amount: number) {
  return _formatPrice(amount, store.tenant?.currency_code ?? 'TRY')
}
</script>
