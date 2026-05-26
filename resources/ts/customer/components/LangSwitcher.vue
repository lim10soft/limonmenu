<template>
  <div class="relative" ref="containerRef">
    <button
      @click="open = !open"
      class="flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white rounded-full px-3 py-1.5 text-sm font-medium transition-colors"
    >
      <span>{{ currentFlag }}</span>
      <span class="hidden sm:inline">{{ currentLabel }}</span>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        class="absolute top-full mt-2 right-0 bg-white rounded-2xl shadow-xl z-50 py-1 min-w-[160px] max-h-80 overflow-y-auto border border-gray-100"
      >
        <button
          v-for="lang in visibleLanguages"
          :key="lang.code"
          @click="select(lang.code)"
          class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors text-left"
          :class="lang.code === currentLang ? 'text-orange-500 font-semibold' : 'text-gray-700'"
        >
          <span class="text-base">{{ lang.flag }}</span>
          <span>{{ lang.label }}</span>
          <svg v-if="lang.code === currentLang" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ms-auto text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
          </svg>
        </button>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { LANGUAGES, setLanguage } from '@/i18n'
import { useMenuStore } from '../stores/menuStore'

const { locale } = useI18n()
const menuStore = useMenuStore()
const open = ref(false)
const containerRef = ref<HTMLElement | null>(null)

const currentLang = computed(() => locale.value)
const currentFlag = computed(() => LANGUAGES.find(l => l.code === locale.value)?.flag ?? '🌐')
const currentLabel = computed(() => LANGUAGES.find(l => l.code === locale.value)?.label ?? '')

const visibleLanguages = computed(() => {
  const active = menuStore.activeLanguages
  if (!active.length) return LANGUAGES
  return LANGUAGES.filter((l) => active.includes(l.code))
})

async function select(code: string) {
  setLanguage(code)
  open.value = false
  await menuStore.reloadWithLang(code)
}

function onClickOutside(e: MouseEvent) {
  if (containerRef.value && !containerRef.value.contains(e.target as Node)) {
    open.value = false
  }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))
</script>
