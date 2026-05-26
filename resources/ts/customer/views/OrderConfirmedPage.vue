<template>
  <div class="flex flex-col items-center justify-center min-h-screen px-6 text-center gap-5">
    <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
    <div>
      <h1 class="text-2xl font-bold text-gray-900">{{ t('order.confirmed') }}</h1>
      <p class="text-gray-500 mt-2">{{ t('order.confirmedDesc') }}</p>
      <p class="text-sm text-gray-400 mt-1">{{ t('order.number') }}: #{{ route.params.orderId }}</p>
    </div>
    <button @click="goBack" class="mt-4 px-6 py-3 bg-orange-500 text-white rounded-xl font-semibold">
      {{ t('order.backToMenu') }}
    </button>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()

function goBack() {
  router.push({ name: 'menu', params: { slug: route.params.slug, token: route.params.token } })
}
</script>
