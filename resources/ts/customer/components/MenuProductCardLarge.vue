<template>
  <div style="border:1px solid #e5e0db; padding:14px 12px; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08); margin-bottom:10px; display:flex; flex-direction:column; align-items:center;">

    <!-- Resim -->
    <div
      style="width:160px; height:130px; border-radius:10px; overflow:hidden; flex-shrink:0; cursor:pointer; margin-bottom:8px;"
      @click="product.image && (lightbox = true)"
    >
      <img
        v-if="product.image"
        :src="product.image"
        style="width:100%; height:100%; object-fit:cover; display:block;"
      />
      <div
        v-else
        style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#e8ddd4;"
      >
        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px;color:#c4b5a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
    </div>

    <!-- Ürün adı -->
    <p style="font-weight:700; font-size:14px; color:#1a1a1a; line-height:1.3; margin-bottom:3px; text-align:center; width:100%;">{{ product.name }}</p>

    <!-- Açıklama -->
    <p
      v-if="product.description"
      style="font-size:11px; color:#7a6e67; line-height:1.4; margin-bottom:6px; text-align:center; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; width:100%;"
    >{{ product.description }}</p>

    <!-- Tükendi -->
    <template v-if="product.in_stock === false">
      <span style="font-size:12px; font-weight:600; color:#dc2626; background:#fee2e2; padding:3px 10px; border-radius:99px; margin-top:4px;">Bu ürün şuan için tükendi</span>
    </template>

    <!-- Stokta: Birimler -->
    <template v-else-if="product.units && product.units.length > 0">
      <div
        v-for="(unit, i) in product.units"
        :key="i"
        style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:3px;"
      >
        <span style="font-weight:700; font-size:13px;">
          <span style="filter:brightness(0.7);" :style="{ color: themeColor || '#f97316' }">{{ unit.label || 'Adet' }} -</span><span :style="{ color: themeColor || '#f97316' }"> {{ formatPrice(unit.price) }}</span>
        </span>
        <button
          v-if="ordersEnabled"
          @click="emit('add', { product, unitLabel: unit.label ?? undefined, unitPrice: unit.price })"
          style="width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; font-weight:700; border:none; cursor:pointer; flex-shrink:0;"
          :style="{ backgroundColor: themeColor || '#f97316' }"
        >+</button>
      </div>
    </template>

    <!-- Stokta: Tek fiyat -->
    <template v-else>
      <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:3px;">
        <span style="font-weight:700; font-size:13px;" :style="{ color: themeColor || '#f97316' }">
          {{ formatPrice(product.price) }}
        </span>
        <button
          v-if="ordersEnabled"
          @click="emit('add', { product })"
          style="width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; font-weight:700; border:none; cursor:pointer; flex-shrink:0;"
          :style="{ backgroundColor: themeColor || '#f97316' }"
        >+</button>
      </div>
    </template>

    <!-- Uyumluluk Bölümü -->
    <div v-if="hasCompliance" style="width:100%; margin-top:8px;">

      <!-- Satır 1: Kalori + Diyet Etiketleri -->
      <div v-if="hasDietary" style="display:flex; flex-wrap:wrap; justify-content:center; gap:4px; margin-bottom:5px;">
        <span v-if="product.calories" style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; background:#f0ece8; color:#7a6e67; letter-spacing:0.01em;">
          🔥 {{ product.calories }} kcal
        </span>
        <span v-if="product.is_vegan" style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; background:#dcfce7; color:#15803d;">
          🌱 Vegan
        </span>
        <span v-else-if="product.is_vegetarian" style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; background:#dcfce7; color:#15803d;">
          🥦 Vejetaryen
        </span>
        <span v-if="product.has_alcohol" style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; background:#fee2e2; color:#b91c1c;">
          🍺 Alkol
        </span>
        <span v-if="product.has_pork" style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; background:#fee2e2; color:#b91c1c;">
          🐷 Domuz
        </span>
      </div>

      <!-- Satır 2: Alerjenler -->
      <div v-if="product.allergens && product.allergens.length"
           style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:5px 8px; display:flex; flex-wrap:wrap; align-items:center; gap:3px; justify-content:center;">
        <span style="font-size:9px; font-weight:700; color:#b45309; text-transform:uppercase; letter-spacing:0.04em; width:100%; text-align:center; margin-bottom:3px;">⚠ Alerjen İçerir</span>
        <span
          v-for="a in product.allergens"
          :key="a"
          style="font-size:10px; font-weight:600; padding:2px 7px; border-radius:4px; background:#fef3c7; color:#92400e; border:1px solid #fde68a;"
        >{{ ALLERGEN_LABELS[a] || a }}</span>
      </div>

    </div>
  </div>

  <!-- Lightbox -->
  <Teleport to="body">
    <Transition name="lb">
      <div
        v-if="lightbox"
        @click="lightbox = false"
        style="position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.92); display:flex; align-items:center; justify-content:center; padding:16px;"
      >
        <img
          :src="product.image!"
          style="max-width:100%; max-height:100%; object-fit:contain; border-radius:12px;"
          @click.stop
        />
        <button
          @click="lightbox = false"
          style="position:absolute; top:16px; right:16px; background:rgba(255,255,255,0.15); border:none; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;"
        >
          <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
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

const lightbox = ref(false)

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

<style scoped>
.lb-enter-active, .lb-leave-active { transition: opacity 0.2s ease; }
.lb-enter-from, .lb-leave-to { opacity: 0; }
</style>
