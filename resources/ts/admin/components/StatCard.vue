<template>
  <div class="sc-card">
    <div class="sc-icon-wrap" :class="colorClasses.bg">
      <svg class="sc-icon" :class="colorClasses.text" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
           v-html="icon" />
    </div>
    <div class="sc-body">
      <p class="sc-label">{{ label }}</p>
      <p class="sc-value">{{ value }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  icon: string
  label: string
  value: string | number
  color: 'orange' | 'green' | 'blue' | 'purple'
}>()

const colorClasses = computed(() => ({
  orange: { bg: 'sc-bg-orange', text: 'sc-text-orange' },
  green:  { bg: 'sc-bg-green',  text: 'sc-text-green'  },
  blue:   { bg: 'sc-bg-blue',   text: 'sc-text-blue'   },
  purple: { bg: 'sc-bg-purple', text: 'sc-text-purple' },
}[props.color]))
</script>

<style scoped>
.sc-card {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 16px;
  padding: 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,.05);
  transition: box-shadow .2s, transform .2s;
}
.sc-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); transform: translateY(-1px); }

.sc-icon-wrap {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sc-bg-orange { background: #fff7ed; }
.sc-bg-green  { background: #f0fdf4; }
.sc-bg-blue   { background: #eff6ff; }
.sc-bg-purple { background: #faf5ff; }

.sc-icon { width: 22px; height: 22px; }
.sc-text-orange { color: #f97316; }
.sc-text-green  { color: #16a34a; }
.sc-text-blue   { color: #3b82f6; }
.sc-text-purple { color: #9333ea; }

.sc-body { min-width: 0; }
.sc-label { font-size: 11px; color: #94a3b8; font-weight: 600; letter-spacing: .3px; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sc-value { font-size: 20px; font-weight: 800; color: #1e293b; line-height: 1; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
