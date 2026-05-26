<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-end justify-center" style="background:rgba(0,0,0,0.5);" @click.self="$emit('close')">
      <div class="w-full max-w-lg rounded-t-2xl p-5" style="background:#faeee0; max-height:90vh; overflow-y:auto;">

        <!-- Başlık -->
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-bold text-gray-900" style="font-size:17px;">{{ type === 'complaint' ? 'Şikayet Bildir' : 'Değerlendirme Yap' }}</h2>
          <button @click="$emit('close')" style="background:none;border:none;cursor:pointer;font-size:20px;color:#9ca3af;">✕</button>
        </div>

        <!-- Tip seçimi -->
        <div class="flex gap-2 mb-4">
          <button
            @click="type = 'review'"
            class="flex-1 py-2 rounded-xl text-sm font-semibold border transition-colors"
            :style="type === 'review' ? `background:${themeColor};color:#fff;border-color:${themeColor}` : 'background:#fff;color:#6b7280;border-color:#e5e7eb'"
          >⭐ Yorum</button>
          <button
            @click="type = 'complaint'"
            class="flex-1 py-2 rounded-xl text-sm font-semibold border transition-colors"
            :style="type === 'complaint' ? 'background:#ef4444;color:#fff;border-color:#ef4444' : 'background:#fff;color:#6b7280;border-color:#e5e7eb'"
          >⚠️ Şikayet</button>
        </div>

        <!-- Yıldız derecelendirme (sadece yorum tipinde) -->
        <div v-if="type === 'review'" class="flex justify-center gap-2 mb-4">
          <button
            v-for="n in 5"
            :key="n"
            @click="rating = n"
            style="background:none;border:none;cursor:pointer;font-size:32px;line-height:1;padding:2px;"
          >{{ n <= rating ? '★' : '☆' }}</button>
        </div>

        <!-- İsim (opsiyonel) -->
        <div class="mb-3">
          <input
            v-model="name"
            type="text"
            placeholder="Adınız (opsiyonel)"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
            style="background:#fff;"
          />
        </div>

        <!-- Yorum / Şikayet metni -->
        <div class="mb-4">
          <textarea
            v-model="comment"
            :placeholder="type === 'complaint' ? 'Şikayetinizi buraya yazın...' : 'Yorumunuzu buraya yazın...'"
            rows="4"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
            style="background:#fff;"
          ></textarea>
        </div>

        <!-- Gönder butonu -->
        <button
          @click="submit"
          :disabled="sending || (!comment.trim() && type === 'complaint')"
          class="w-full py-3 rounded-xl text-white font-bold text-sm transition-opacity disabled:opacity-50"
          :style="{ backgroundColor: type === 'complaint' ? '#ef4444' : (themeColor || '#f97316') }"
        >{{ sending ? 'Gönderiliyor...' : 'Gönder' }}</button>

        <!-- Başarı mesajı -->
        <div v-if="sent" class="mt-3 text-center text-sm text-green-600 font-medium">
          {{ type === 'complaint' ? '✓ Şikayetiniz iletildi, teşekkürler.' : '✓ Değerlendirmeniz için teşekkürler!' }}
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import http from '@/api/http'

const props = defineProps<{
  slug: string
  themeColor?: string
}>()

const emit = defineEmits<{ (e: 'close'): void }>()

const type = ref<'review' | 'complaint'>('review')
const rating = ref(0)
const comment = ref('')
const name = ref('')
const sending = ref(false)
const sent = ref(false)

async function submit() {
  if (sending.value) return
  sending.value = true
  try {
    await http.post(`/menu/${props.slug}/review`, {
      type: type.value,
      rating: type.value === 'review' && rating.value > 0 ? rating.value : null,
      comment: comment.value.trim() || null,
      name: name.value.trim() || null,
    })
    sent.value = true
    setTimeout(() => emit('close'), 2000)
  } finally {
    sending.value = false
  }
}
</script>
