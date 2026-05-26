<template>
  <Teleport to="body">
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-2 pointer-events-none" style="min-width:280px;max-width:90vw;">
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl text-sm font-medium pointer-events-auto"
          :style="toastStyle(toast.type)"
        >
          <!-- İkon -->
          <span class="shrink-0 flex items-center justify-center rounded-full w-6 h-6" :style="iconBg(toast.type)">
            <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else-if="toast.type === 'error'" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </span>
          <span>{{ toast.message }}</span>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { useToast, type ToastType } from '../composables/useToast'

const { toasts } = useToast()

function toastStyle(type: ToastType) {
  if (type === 'success') return 'background:#fff; border:1.5px solid #22c55e; color:#15803d;'
  if (type === 'error')   return 'background:#fff; border:1.5px solid #ef4444; color:#dc2626;'
  return 'background:#fff; border:1.5px solid #3b82f6; color:#1d4ed8;'
}

function iconBg(type: ToastType) {
  if (type === 'success') return 'background:#22c55e;'
  if (type === 'error')   return 'background:#ef4444;'
  return 'background:#3b82f6;'
}
</script>

<style scoped>
.toast-enter-active { transition: all 0.25s cubic-bezier(0.4,0,0.2,1); }
.toast-leave-active { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
.toast-enter-from   { opacity: 0; transform: translateY(16px) scale(0.96); }
.toast-leave-to     { opacity: 0; transform: translateY(8px) scale(0.96); }
</style>
