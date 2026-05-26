<template>
  <div class="relative" ref="containerRef">
    <button
      @click="open = !open"
      class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-sm text-gray-500 hover:bg-gray-100 transition-colors"
    >
      <span>{{ currentFlag }}</span>
      <span class="hidden lg:inline text-xs font-medium">{{ currentLabel }}</span>
      <svg class="h-3 w-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="open"
        class="absolute top-full mt-1 end-0 bg-white rounded-2xl shadow-xl z-50 py-1 w-44 max-h-80 overflow-y-auto border border-gray-100"
      >
        <button
          v-for="lang in ADMIN_LANGUAGES"
          :key="lang.code"
          @click="select(lang.code)"
          class="w-full flex items-center gap-2.5 px-3 py-2 text-sm transition-colors text-start"
          :class="lang.code === currentLang ? 'text-orange-500 font-semibold bg-orange-50' : 'text-gray-700 hover:bg-gray-50'"
        >
          <span>{{ lang.flag }}</span>
          <span>{{ lang.label }}</span>
        </button>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { ADMIN_LANGUAGES, setAdminLanguage } from '../i18n'

const { locale } = useI18n()
const open = ref(false)
const containerRef = ref<HTMLElement | null>(null)

const currentLang  = computed(() => locale.value)
const currentFlag  = computed(() => ADMIN_LANGUAGES.find(l => l.code === locale.value)?.flag ?? '🌐')
const currentLabel = computed(() => ADMIN_LANGUAGES.find(l => l.code === locale.value)?.label ?? '')

function select(code: string) {
  setAdminLanguage(code)
  open.value = false
}

function onClickOutside(e: MouseEvent) {
  if (containerRef.value && !containerRef.value.contains(e.target as Node)) open.value = false
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))
</script>
