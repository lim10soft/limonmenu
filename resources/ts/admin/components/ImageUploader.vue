<script setup lang="ts">
import { ref } from 'vue'
import http from '@/api/http'

const props = defineProps<{ modelValue: string; type?: string }>()
const emit = defineEmits<{ 'update:modelValue': [string] }>()

const uploading = ref(false)
const fileInput = ref<HTMLInputElement>()

async function onFileChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return
  uploading.value = true
  try {
    const fd = new FormData()
    fd.append('image', file)
    if (props.type) fd.append('type', props.type)
    const res = await http.post('/admin/upload-image', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    emit('update:modelValue', res.data.url)
  } finally {
    uploading.value = false
    if (fileInput.value) fileInput.value.value = ''
  }
}
</script>

<template>
  <div class="space-y-2">
    <div class="flex gap-2 items-center">
      <input
        :value="modelValue"
        @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        type="url"
        placeholder="https://..."
        class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
      />
      <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
      <button
        type="button"
        @click="fileInput?.click()"
        :disabled="uploading"
        class="shrink-0 flex items-center gap-1.5 px-3 py-2 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-60 whitespace-nowrap"
      >
        <svg v-if="!uploading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
        </svg>
        <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        {{ uploading ? 'Yükleniyor...' : 'Bilgisayardan Seç' }}
      </button>
    </div>
    <img v-if="modelValue" :src="modelValue" class="h-16 rounded-xl object-cover border border-gray-100" />
  </div>
</template>
