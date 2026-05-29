<template>
  <Teleport to="body">
    <Transition name="confirm-fade">
      <div
        v-if="confirmState.visible"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @mousedown.self="cancel"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" />

        <!-- Dialog -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden confirm-panel">
          <!-- Top accent bar -->
          <div class="h-1 w-full" :class="accentBar" />

          <div class="p-6">
            <!-- Icon + Title -->
            <div class="flex items-start gap-3 mb-3">
              <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="iconBg">
                <!-- Danger / Warning: trash -->
                <svg v-if="confirmState.type === 'danger'" class="w-5 h-5" :class="iconColor" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                </svg>
                <!-- Warning: triangle -->
                <svg v-else-if="confirmState.type === 'warning'" class="w-5 h-5" :class="iconColor" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <!-- Info: question -->
                <svg v-else class="w-5 h-5" :class="iconColor" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0 pt-0.5">
                <p v-if="confirmState.title" class="font-semibold text-gray-900 text-sm leading-tight mb-1">{{ confirmState.title }}</p>
                <p class="text-gray-600 text-sm leading-relaxed">{{ confirmState.message }}</p>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 mt-5">
              <button
                @click="cancel"
                class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors"
              >{{ confirmState.cancelText }}</button>
              <button
                @click="ok"
                class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors"
                :class="confirmBtn"
              >{{ confirmState.confirmText }}</button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useConfirm } from '../composables/useConfirm'

const { confirmState, answer } = useConfirm()

function ok()     { answer(true)  }
function cancel() { answer(false) }

const accentBar = computed(() => {
  if (confirmState.value.type === 'danger')  return 'bg-red-500'
  if (confirmState.value.type === 'warning') return 'bg-amber-400'
  return 'bg-orange-500'
})

const iconBg = computed(() => {
  if (confirmState.value.type === 'danger')  return 'bg-red-50'
  if (confirmState.value.type === 'warning') return 'bg-amber-50'
  return 'bg-orange-50'
})

const iconColor = computed(() => {
  if (confirmState.value.type === 'danger')  return 'text-red-500'
  if (confirmState.value.type === 'warning') return 'text-amber-500'
  return 'text-orange-500'
})

const confirmBtn = computed(() => {
  if (confirmState.value.type === 'danger')  return 'bg-red-500 hover:bg-red-600'
  if (confirmState.value.type === 'warning') return 'bg-amber-500 hover:bg-amber-600'
  return 'bg-orange-500 hover:bg-orange-600'
})
</script>

<style scoped>
.confirm-fade-enter-active { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
.confirm-fade-leave-active { transition: all 0.15s cubic-bezier(0.4,0,0.2,1); }
.confirm-fade-enter-from,
.confirm-fade-leave-to    { opacity: 0; }
.confirm-fade-enter-from .confirm-panel { transform: scale(0.95) translateY(8px); }
.confirm-fade-enter-active .confirm-panel { transition: transform 0.2s cubic-bezier(0.34,1.56,0.64,1); }
</style>
