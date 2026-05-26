<template>
  <div class="lm-auth-root" :class="theme === 'light' ? 'lm-light' : ''">

    <!-- Theme toggle (fixed top-right) -->
    <button class="lm-toggle" @click="toggle" :title="theme === 'dark' ? 'Açık moda geç' : 'Koyu moda geç'">
      <!-- Sun icon (shown in dark mode → click to go light) -->
      <svg v-if="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
      </svg>
      <!-- Moon icon (shown in light mode → click to go dark) -->
      <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
      </svg>
    </button>

    <!-- ── Sol marka paneli ── -->
    <div class="lm-brand">
      <div class="lm-brand-inner">
        <img :src="'/limonpos.png'" alt="LimonMenü" class="lm-brand-logo" />
        <h1 class="lm-brand-title">LimonMenü</h1>
        <p class="lm-brand-sub">QR MENÜ SİSTEMİ</p>
        <div class="lm-divider"></div>
        <p class="lm-brand-cta-text">LimonMenü ile entegre restoran yönetim yazılımımıza göz atın</p>
        <div class="lm-cta-buttons">
          <button class="lm-cta-btn lm-cta-primary" @click="modalOpen = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Sitemize Göz Atın
          </button>
          <button class="lm-cta-btn lm-cta-secondary" @click="modalOpen = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="5 3 19 12 5 21 5 3"/>
            </svg>
            Demo İncele
          </button>
        </div>
      </div>
    </div>

    <!-- ── Modal ── -->
    <Transition name="lm-modal-fade">
      <div v-if="modalOpen" class="lm-modal-overlay" @click.self="modalOpen = false">
        <div class="lm-modal">
          <button class="lm-modal-close" @click="modalOpen = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>

          <div class="lm-modal-header">
            <img :src="'/limonpos.png'" alt="LimonPos" class="lm-modal-logo" />
            <div>
              <h3 class="lm-modal-title">LimonPos</h3>
              <p class="lm-modal-subtitle">Restoran Yönetim Yazılımı</p>
            </div>
          </div>

          <p class="lm-modal-desc">
            LimonMenü ile tam entegre restoran yönetim yazılımımıza göz atabilirsiniz.
            Tek platformdan tüm operasyonunuzu yönetin.
          </p>

          <ul class="lm-modal-features">
            <li>
              <span class="lm-mf-icon">🛒</span>
              <div>
                <strong>Entegre Online Sipariş Yönetimi</strong>
                <span>Getir, Yemeksepeti, kendi siteniz — tek ekranda</span>
              </div>
            </li>
            <li>
              <span class="lm-mf-icon">🪑</span>
              <div>
                <strong>Masa Takip Yönetimi</strong>
                <span>Anlık masa durumu, adisyon ve sipariş takibi</span>
              </div>
            </li>
            <li>
              <span class="lm-mf-icon">🧑‍🍳</span>
              <div>
                <strong>Garson Uygulamaları</strong>
                <span>Mobil garson paneli ile masadan sipariş alma</span>
              </div>
            </li>
            <li>
              <span class="lm-mf-icon">🖨️</span>
              <div>
                <strong>Yazarkasa &amp; POS Entegrasyonu</strong>
                <span>Fiş yazıcı, barkod okuyucu, ÖKC entegrasyonu</span>
              </div>
            </li>
          </ul>

          <div class="lm-modal-actions">
            <a href="https://limonpos.net" target="_blank" rel="noopener" class="lm-modal-btn lm-modal-btn-outline">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
              </svg>
              Sitemize Git
            </a>
            <a href="https://demo.limonpos.net/dashboard/pos" target="_blank" rel="noopener" class="lm-modal-btn lm-modal-btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="5 3 19 12 5 21 5 3"/>
              </svg>
              Demo Görüntüle
            </a>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Sağ form paneli ── -->
    <div class="lm-form-side">
      <div class="lm-form-inner">

        <div class="lm-form-header">
          <img :src="'/limonpos.png'" alt="LimonMenü" class="lm-form-logo" />
          <h2 class="lm-form-title">
            {{ mode === 'login' ? t('login.title') : t('register.title') }}
          </h2>
          <p class="lm-form-sub">
            {{ mode === 'login' ? t('login.subtitle') : t('register.subtitle') }}
          </p>
        </div>

        <div class="lm-card">

          <!-- Giriş Formu -->
          <form v-if="mode === 'login'" @submit.prevent="handleLogin" class="lm-fields">
            <div class="lm-field">
              <label>{{ t('login.email') }}</label>
              <input v-model="loginForm.email" type="email" required placeholder="restoran@example.com" />
            </div>
            <div class="lm-field">
              <label>{{ t('login.password') }}</label>
              <input v-model="loginForm.password" type="password" required placeholder="••••••••" />
            </div>

            <label class="lm-remember">
              <input v-model="remember" type="checkbox" />
              <span>{{ t('login.remember') }}</span>
            </label>

            <p v-if="error" class="lm-error">{{ error }}</p>

            <button type="submit" :disabled="loading" class="lm-btn-primary">
              <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
              </svg>
              <span v-if="loading" class="lm-spin">⏳</span>
              {{ loading ? t('login.submitting') : t('login.submit') }}
            </button>

            <p class="lm-switch-row">
              {{ t('login.no_account') }}
              <button type="button" @click="switchMode('register')">{{ t('login.sign_up') }}</button>
            </p>
          </form>

          <!-- Kayıt Formu -->
          <form v-else @submit.prevent="handleRegister" class="lm-fields">
            <div class="lm-field">
              <label>{{ t('register.restaurant_name') }}</label>
              <input v-model="registerForm.restaurant_name" type="text" required placeholder="Restoran Adınız" />
            </div>
            <div class="lm-field">
              <label>{{ t('register.email') }}</label>
              <input v-model="registerForm.email" type="email" required placeholder="restoran@example.com" />
            </div>
            <div class="lm-field">
              <label>{{ t('register.password') }}</label>
              <input v-model="registerForm.password" type="password" required minlength="6" placeholder="••••••••" />
            </div>
            <div class="lm-field">
              <label>{{ t('register.password_confirm') }}</label>
              <input v-model="registerForm.password_confirmation" type="password" required placeholder="••••••••" />
            </div>

            <p v-if="error" class="lm-error">{{ error }}</p>

            <button type="submit" :disabled="loading" class="lm-btn-primary">
              <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/>
                <line x1="22" y1="11" x2="16" y2="11"/>
              </svg>
              <span v-if="loading" class="lm-spin">⏳</span>
              {{ loading ? t('register.submitting') : t('register.submit') }}
            </button>

            <p class="lm-switch-row">
              {{ t('register.has_account') }}
              <button type="button" @click="switchMode('login')">{{ t('register.sign_in') }}</button>
            </p>
          </form>

        </div>

        <!-- Dil seçici -->
        <div class="lm-lang-wrap" ref="langRef">
          <button @click="langOpen = !langOpen" class="lm-lang-btn">
            <span>{{ currentFlag }}</span>
            <span>{{ currentLabel }}</span>
            <svg class="lm-chevron" :class="langOpen ? 'lm-chevron-up' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <Transition name="lm-fade">
            <div v-if="langOpen" class="lm-lang-dropdown">
              <button
                v-for="lang in ADMIN_LANGUAGES"
                :key="lang.code"
                @click="selectLang(lang.code)"
                class="lm-lang-item"
                :class="locale === lang.code ? 'lm-lang-active' : ''"
              >
                <span>{{ lang.flag }}</span>
                <span>{{ lang.label }}</span>
              </button>
            </div>
          </Transition>
        </div>

        <p class="lm-copy">© {{ new Date().getFullYear() }} LimonMenü</p>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../stores/authStore'
import { ADMIN_LANGUAGES, setAdminLanguage } from '../i18n'
import { useTheme } from '../composables/useTheme'

const { t, locale } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const { theme, toggle } = useTheme('light')

const mode = ref<'login' | 'register'>('login')
const loading = ref(false)
const error = ref('')
const remember = ref(false)
const langOpen = ref(false)
const langRef = ref<HTMLElement | null>(null)
const modalOpen = ref(false)

const loginForm = ref({ email: '', password: '' })
const registerForm = ref({ restaurant_name: '', email: '', password: '', password_confirmation: '' })

const currentFlag  = computed(() => ADMIN_LANGUAGES.find(l => l.code === locale.value)?.flag ?? '🌐')
const currentLabel = computed(() => ADMIN_LANGUAGES.find(l => l.code === locale.value)?.label ?? '')

function switchMode(m: 'login' | 'register') { mode.value = m; error.value = '' }
function selectLang(code: string) { setAdminLanguage(code); langOpen.value = false }
function onClickOutside(e: MouseEvent) {
  if (langRef.value && !langRef.value.contains(e.target as Node)) langOpen.value = false
}
onMounted(() => document.addEventListener('click', onClickOutside))
onUnmounted(() => document.removeEventListener('click', onClickOutside))

async function handleLogin() {
  loading.value = true; error.value = ''
  try {
    await auth.login(loginForm.value.email, loginForm.value.password, remember.value)
    router.push({ name: 'dashboard' })
  } catch { error.value = t('login.error') }
  finally { loading.value = false }
}

async function handleRegister() {
  loading.value = true; error.value = ''
  try {
    await auth.register(registerForm.value.restaurant_name, registerForm.value.email, registerForm.value.password, registerForm.value.password_confirmation)
    router.push({ name: 'dashboard' })
  } catch (e: any) {
    const status = e?.response?.status
    const msg = e?.response?.data?.errors?.email?.[0]
    if (status === 422 && msg?.includes('taken')) error.value = t('register.error_duplicate')
    else if (status === 422 && msg) error.value = msg
    else error.value = t('register.error_generic')
  } finally { loading.value = false }
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

/* ══ CSS VARIABLES — DARK (default) ══ */
.lm-auth-root {
  --bg:              #080f1a;
  --brand-from:      #0d1b2e;
  --brand-to:        #111827;
  --brand-title:     #f8fafc;
  --brand-sub:       rgba(148,163,184,.7);
  --feat-bg:         rgba(255,255,255,.04);
  --feat-border:     rgba(255,255,255,.07);
  --feat-text:       rgba(203,213,225,.9);
  --feat-hbg:        rgba(249,115,22,.07);
  --feat-hborder:    rgba(249,115,22,.2);
  --form-bg:         #0a1422;
  --form-border:     rgba(255,255,255,.04);
  --title-color:     #f1f5f9;
  --sub-color:       rgba(148,163,184,.6);
  --card-bg:         rgba(255,255,255,.025);
  --card-border:     rgba(255,255,255,.07);
  --label-color:     rgba(148,163,184,.8);
  --input-bg:        #0f1929;
  --input-border:    rgba(255,255,255,.08);
  --input-text:      #f0f4ff;
  --input-ph:        rgba(100,130,170,.4);
  --input-fborder:   rgba(249,115,22,.5);
  --input-fshadow:   rgba(249,115,22,.1);
  --remember-text:   rgba(148,163,184,.7);
  --switch-text:     rgba(148,163,184,.6);
  --lang-btn-bg:     rgba(255,255,255,.04);
  --lang-btn-border: rgba(255,255,255,.08);
  --lang-btn-text:   rgba(148,163,184,.8);
  --lang-dd-bg:      #1e293b;
  --lang-dd-border:  rgba(255,255,255,.08);
  --lang-item-text:  rgba(203,213,225,.85);
  --lang-item-hover: rgba(255,255,255,.06);
  --copy-color:      rgba(100,130,170,.35);
  --toggle-bg:       rgba(255,255,255,.07);
  --toggle-border:   rgba(255,255,255,.1);
  --toggle-color:    #94a3b8;
}

/* ══ CSS VARIABLES — LIGHT ══ */
.lm-auth-root.lm-light {
  --bg:              #f1f5f9;
  --brand-from:      #fff7ed;
  --brand-to:        #fed7aa;
  --brand-title:     #1e293b;
  --brand-sub:       rgba(100,116,139,.8);
  --feat-bg:         rgba(255,255,255,.75);
  --feat-border:     rgba(0,0,0,.08);
  --feat-text:       #475569;
  --feat-hbg:        rgba(249,115,22,.08);
  --feat-hborder:    rgba(249,115,22,.35);
  --form-bg:         #ffffff;
  --form-border:     rgba(0,0,0,.05);
  --title-color:     #1e293b;
  --sub-color:       #94a3b8;
  --card-bg:         #f8fafc;
  --card-border:     #e2e8f0;
  --label-color:     #64748b;
  --input-bg:        #ffffff;
  --input-border:    #e2e8f0;
  --input-text:      #1e293b;
  --input-ph:        #94a3b8;
  --input-fborder:   rgba(249,115,22,.5);
  --input-fshadow:   rgba(249,115,22,.1);
  --remember-text:   #64748b;
  --switch-text:     #64748b;
  --lang-btn-bg:     #f1f5f9;
  --lang-btn-border: #e2e8f0;
  --lang-btn-text:   #475569;
  --lang-dd-bg:      #ffffff;
  --lang-dd-border:  #e2e8f0;
  --lang-item-text:  #475569;
  --lang-item-hover: #f1f5f9;
  --copy-color:      #cbd5e1;
  --toggle-bg:       #f1f5f9;
  --toggle-border:   #e2e8f0;
  --toggle-color:    #64748b;
}

/* ══ THEME TOGGLE ══ */
.lm-toggle {
  position: fixed;
  top: 16px;
  right: 16px;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  background: var(--toggle-bg);
  border: 1px solid var(--toggle-border);
  border-radius: 10px;
  color: var(--toggle-color);
  cursor: pointer;
  transition: all .2s;
}
.lm-toggle:hover {
  background: rgba(249,115,22,.12);
  border-color: rgba(249,115,22,.3);
  color: #f97316;
}

/* ══ ROOT ══ */
.lm-auth-root {
  display: flex;
  min-height: 100vh;
  background: var(--bg);
  font-family: 'Nunito', 'Inter', system-ui, sans-serif;
  transition: background .25s;
}

/* ══ LEFT BRAND PANEL ══ */
.lm-brand {
  flex: 0 0 42%;
  background: linear-gradient(160deg, var(--brand-from) 0%, var(--brand-to) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 40px;
  position: relative;
  overflow: hidden;
  transition: background .25s;
}
.lm-brand::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse 80% 60% at 50% 35%, rgba(249,115,22,.12) 0%, transparent 70%);
}
.lm-brand-inner {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}
.lm-brand-logo {
  width: 100px;
  height: 100px;
  object-fit: contain;
  margin-bottom: 20px;
  filter: drop-shadow(0 8px 24px rgba(249,115,22,.4));
}
.lm-brand-title {
  font-size: 32px;
  font-weight: 800;
  color: var(--brand-title);
  margin: 0 0 5px;
  letter-spacing: -.5px;
}
.lm-brand-sub {
  color: var(--brand-sub);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 2px;
  margin: 0 0 28px;
}
.lm-divider {
  width: 40px;
  height: 3px;
  background: linear-gradient(90deg, #f97316, #fb923c);
  border-radius: 2px;
  margin-bottom: 28px;
}
.lm-features {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
  max-width: 260px;
}
.lm-features li {
  display: flex;
  align-items: center;
  gap: 11px;
  background: var(--feat-bg);
  border: 1px solid var(--feat-border);
  border-radius: 12px;
  padding: 11px 14px;
  color: var(--feat-text);
  font-size: 13px;
  font-weight: 600;
  text-align: left;
  transition: background .2s, border-color .2s;
}
.lm-features li:hover {
  background: var(--feat-hbg);
  border-color: var(--feat-hborder);
}
.lm-fi { font-size: 16px; flex-shrink: 0; }

/* ══ BRAND CTA ══ */
.lm-brand-cta-text {
  color: var(--brand-sub);
  font-size: 12px;
  font-weight: 500;
  text-align: center;
  margin: 0 0 18px;
  max-width: 240px;
  line-height: 1.5;
}
.lm-cta-buttons {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
  max-width: 240px;
}
.lm-cta-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
  font-family: inherit;
  letter-spacing: .3px;
}
.lm-cta-primary {
  background: linear-gradient(135deg, #f97316, #ea580c);
  color: #fff;
  border: none;
  box-shadow: 0 4px 14px rgba(249,115,22,.35);
}
.lm-cta-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(249,115,22,.5); }
.lm-cta-secondary {
  background: rgba(255,255,255,.06);
  color: rgba(203,213,225,.9);
  border: 1px solid rgba(255,255,255,.1);
}
.lm-auth-root.lm-light .lm-cta-secondary {
  background: rgba(0,0,0,.04);
  color: #475569;
  border: 1px solid rgba(0,0,0,.1);
}
.lm-cta-secondary:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.2); }
.lm-auth-root.lm-light .lm-cta-secondary:hover { background: rgba(0,0,0,.08); }

/* ══ MODAL ══ */
.lm-modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 200;
  background: rgba(0,0,0,.65);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  backdrop-filter: blur(4px);
}
.lm-modal {
  background: var(--lang-dd-bg);
  border: 1px solid var(--card-border);
  border-radius: 20px;
  width: 100%;
  max-width: 460px;
  padding: 28px 24px 24px;
  position: relative;
  box-shadow: 0 24px 60px rgba(0,0,0,.5);
}
.lm-modal-close {
  position: absolute;
  top: 14px;
  right: 14px;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--toggle-bg);
  border: 1px solid var(--toggle-border);
  border-radius: 8px;
  color: var(--toggle-color);
  cursor: pointer;
  transition: all .2s;
}
.lm-modal-close:hover { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.2); color: #f87171; }

.lm-modal-header {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 14px;
}
.lm-modal-logo { width: 44px; height: 44px; object-fit: contain; border-radius: 10px; }
.lm-modal-title { font-size: 18px; font-weight: 800; color: var(--title-color); margin: 0 0 2px; }
.lm-modal-subtitle { font-size: 12px; color: var(--sub-color); margin: 0; font-weight: 500; }

.lm-modal-desc {
  font-size: 13px;
  color: var(--lang-item-text);
  line-height: 1.6;
  margin: 0 0 18px;
  padding-bottom: 18px;
  border-bottom: 1px solid var(--card-border);
}

.lm-modal-features {
  list-style: none;
  padding: 0;
  margin: 0 0 22px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.lm-modal-features li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}
.lm-mf-icon {
  font-size: 20px;
  flex-shrink: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--feat-bg);
  border: 1px solid var(--feat-border);
  border-radius: 8px;
}
.lm-modal-features li > div { display: flex; flex-direction: column; gap: 2px; }
.lm-modal-features li strong { font-size: 13px; font-weight: 700; color: var(--title-color); }
.lm-modal-features li span { font-size: 11px; color: var(--sub-color); font-weight: 500; }

.lm-modal-actions {
  display: flex;
  gap: 10px;
}
.lm-modal-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  flex: 1;
  padding: 11px 16px;
  border-radius: 11px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  transition: all .2s;
  font-family: inherit;
  cursor: pointer;
}
.lm-modal-btn-outline {
  background: var(--feat-bg);
  border: 1px solid var(--feat-border);
  color: var(--lang-item-text);
}
.lm-modal-btn-outline:hover { background: var(--feat-hbg); border-color: var(--feat-hborder); color: var(--title-color); }
.lm-modal-btn-primary {
  background: linear-gradient(135deg, #f97316, #ea580c);
  border: none;
  color: #fff;
  box-shadow: 0 4px 14px rgba(249,115,22,.35);
}
.lm-modal-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(249,115,22,.45); }

.lm-modal-fade-enter-active, .lm-modal-fade-leave-active { transition: opacity .2s, transform .2s; }
.lm-modal-fade-enter-from, .lm-modal-fade-leave-to { opacity: 0; transform: scale(.95); }
.lm-modal-fade-enter-to, .lm-modal-fade-leave-from { opacity: 1; transform: scale(1); }

/* ══ RIGHT FORM PANEL ══ */
.lm-form-side {
  flex: 1;
  background: var(--form-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 28px;
  overflow-y: auto;
  border-left: 1px solid var(--form-border);
  transition: background .25s;
}
.lm-form-inner { width: 100%; max-width: 400px; }
.lm-form-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 24px;
  text-align: center;
}
.lm-form-logo {
  width: 52px;
  height: 52px;
  object-fit: contain;
  margin-bottom: 14px;
  filter: drop-shadow(0 4px 12px rgba(249,115,22,.35));
}
.lm-form-title { font-size: 21px; font-weight: 800; color: var(--title-color); margin: 0 0 4px; }
.lm-form-sub { color: var(--sub-color); font-size: 12px; font-weight: 500; margin: 0; }

/* ══ CARD ══ */
.lm-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 18px;
  overflow: hidden;
  transition: background .25s, border-color .25s;
}

/* ══ FIELDS ══ */
.lm-fields { padding: 24px 20px 20px; display: flex; flex-direction: column; gap: 14px; }
.lm-field { display: flex; flex-direction: column; gap: 5px; }
.lm-field label {
  color: var(--label-color);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .8px;
  text-transform: uppercase;
}
.lm-field input {
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  border-radius: 10px;
  color: var(--input-text);
  font-size: 14px;
  font-weight: 500;
  padding: 10px 13px;
  outline: none;
  transition: border-color .2s, box-shadow .2s, background .25s;
  width: 100%;
  font-family: inherit;
}
.lm-field input:focus {
  border-color: var(--input-fborder);
  box-shadow: 0 0 0 3px var(--input-fshadow);
}
.lm-field input::placeholder { color: var(--input-ph); }

.lm-remember { display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none; }
.lm-remember input { accent-color: #f97316; cursor: pointer; }
.lm-remember span { font-size: 12px; color: var(--remember-text); font-weight: 500; }

.lm-error {
  background: rgba(239,68,68,.1);
  border: 1px solid rgba(239,68,68,.25);
  border-radius: 8px;
  color: #f87171;
  font-size: 12px;
  font-weight: 600;
  padding: 8px 12px;
  margin: 0;
}

/* ══ PRIMARY BUTTON ══ */
.lm-btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 12px 20px;
  background: linear-gradient(135deg, #f97316, #ea580c);
  color: #fff;
  border: none;
  border-radius: 11px;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: 1px;
  cursor: pointer;
  transition: transform .2s, box-shadow .2s, opacity .2s;
  box-shadow: 0 4px 14px rgba(249,115,22,.35);
  font-family: inherit;
}
.lm-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(249,115,22,.45); }
.lm-btn-primary:active:not(:disabled) { transform: scale(.97); }
.lm-btn-primary:disabled { opacity: .6; cursor: not-allowed; }

.lm-switch-row { text-align: center; font-size: 12px; color: var(--switch-text); margin: 0; padding-top: 2px; }
.lm-switch-row button {
  background: none; border: none; color: #f97316; font-weight: 700;
  font-size: 12px; cursor: pointer; padding: 0; margin-left: 4px; font-family: inherit;
}
.lm-switch-row button:hover { text-decoration: underline; }

/* ══ LANG ══ */
.lm-lang-wrap { position: relative; display: flex; justify-content: center; margin-top: 18px; }
.lm-lang-btn {
  display: flex; align-items: center; gap: 6px;
  padding: 7px 14px;
  background: var(--lang-btn-bg);
  border: 1px solid var(--lang-btn-border);
  border-radius: 10px;
  color: var(--lang-btn-text);
  font-size: 12px; font-weight: 600; cursor: pointer;
  transition: all .2s; font-family: inherit;
}
.lm-lang-btn:hover { background: rgba(249,115,22,.08); border-color: rgba(249,115,22,.2); color: #f97316; }
.lm-chevron { width: 13px; height: 13px; transition: transform .2s; }
.lm-chevron-up { transform: rotate(180deg); }

.lm-lang-dropdown {
  position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%);
  background: var(--lang-dd-bg);
  border: 1px solid var(--lang-dd-border);
  border-radius: 14px; box-shadow: 0 8px 30px rgba(0,0,0,.3);
  z-index: 50; padding: 6px; width: 160px; max-height: 240px; overflow-y: auto;
}
.lm-lang-item {
  display: flex; align-items: center; gap: 8px; width: 100%;
  padding: 8px 10px; background: none; border: none; border-radius: 8px;
  font-size: 13px; font-weight: 500; color: var(--lang-item-text); cursor: pointer;
  text-align: left; transition: background .15s; font-family: inherit;
}
.lm-lang-item:hover { background: var(--lang-item-hover); }
.lm-lang-active { color: #fb923c !important; font-weight: 700 !important; background: rgba(249,115,22,.08) !important; }

.lm-fade-enter-active, .lm-fade-leave-active { transition: opacity .1s, transform .1s; }
.lm-fade-enter-from, .lm-fade-leave-to { opacity: 0; transform: translateX(-50%) scale(.95); }
.lm-fade-enter-to, .lm-fade-leave-from { opacity: 1; transform: translateX(-50%) scale(1); }

.lm-copy { text-align: center; font-size: 11px; color: var(--copy-color); margin-top: 16px; }

@media (max-width: 900px) {
  .lm-brand { display: none; }
  .lm-form-side { border-left: none; padding: 28px 16px; }
}
</style>
