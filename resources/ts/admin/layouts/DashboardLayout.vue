<template>
  <div class="lm-layout" :class="theme === 'light' ? 'lm-light' : ''">

    <!-- ── Sidebar (Desktop) ── -->
    <aside class="lm-sidebar">
      <div class="lm-sidebar-logo">
        <img :src="'/limonpos.png'" alt="LimonMenü" class="lm-sidebar-icon" />
        <div class="lm-sidebar-brand">
          <span class="lm-brand-name">LimonMenü</span>
          <span class="lm-tenant-name">{{ auth.tenant?.name ?? '' }}</span>
        </div>
        <button class="lm-toggle" @click="toggle" :title="theme === 'dark' ? 'Açık mod' : 'Koyu mod'">
          <svg v-if="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
        </button>
      </div>

      <nav class="lm-nav">
        <router-link
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="lm-nav-item"
          :class="{ 'lm-nav-active': $route.name === item.name }"
        >
          <svg class="lm-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" v-html="item.icon" />
          <span class="lm-nav-label">{{ t(item.labelKey) }}</span>
        </router-link>
      </nav>

      <div class="lm-sidebar-footer">
        <button @click="handleLogout" class="lm-logout-btn">
          <svg class="lm-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          {{ t('nav.logout') }}
        </button>
      </div>
    </aside>

    <!-- ── Mobile top bar ── -->
    <div class="lm-mobile-bar">
      <div class="lm-mobile-brand">
        <img :src="'/limonpos.png'" alt="LimonMenü" class="lm-mobile-icon" />
        <span>{{ auth.tenant?.name ?? 'LimonMenü' }}</span>
      </div>
      <div class="lm-mobile-actions">
        <button class="lm-toggle lm-toggle-mobile" @click="toggle">
          <svg v-if="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
        </button>
        <AdminLangSwitcher />
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lm-hamburger">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- ── Mobile menu overlay ── -->
    <Transition name="lm-overlay">
      <div v-if="mobileMenuOpen" class="lm-mobile-overlay" @click="mobileMenuOpen = false">
        <div class="lm-mobile-menu" @click.stop>
          <div class="lm-mobile-menu-header">
            <img :src="'/limonpos.png'" alt="LimonMenü" class="lm-mobile-icon" />
            <div>
              <p class="lm-brand-name">LimonMenü</p>
              <p class="lm-tenant-name">{{ auth.tenant?.name }}</p>
            </div>
          </div>
          <nav class="lm-nav">
            <router-link
              v-for="item in navItems"
              :key="item.to"
              :to="item.to"
              @click="mobileMenuOpen = false"
              class="lm-nav-item"
              :class="{ 'lm-nav-active': $route.name === item.name }"
            >
              <svg class="lm-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" v-html="item.icon" />
              <span class="lm-nav-label">{{ t(item.labelKey) }}</span>
            </router-link>
          </nav>
          <button @click="handleLogout" class="lm-logout-btn" style="margin-top:8px">
            <svg class="lm-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            {{ t('nav.logout') }}
          </button>
        </div>
      </div>
    </Transition>

    <!-- ── Main content ── -->
    <main class="lm-main">
      <div class="lm-mobile-spacer"></div>
      <div class="lm-topbar"><AdminLangSwitcher /></div>
      <router-view />
    </main>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../stores/authStore'
import AdminLangSwitcher from '../components/AdminLangSwitcher.vue'
import { useTheme } from '../composables/useTheme'

const { t } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const { theme, toggle } = useTheme('dark')
const mobileMenuOpen = ref(false)

onMounted(async () => {
  await auth.fetchMe()
  if (auth.isAdmin && route.name === 'dashboard') {
    router.replace({ name: 'tenants' })
  }
})

// Lucide-style SVG inner paths for each nav item
const ICONS = {
  dashboard:   `<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>`,
  orders:      `<path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6"/><path d="M9 16h6"/>`,
  products:    `<path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/>`,
  categories:  `<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>`,
  tables:      `<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/>`,
  departments: `<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>`,
  reviews:     `<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>`,
  settings:    `<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>`,
  tenants:     `<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>`,
}

const navItems = computed(() => {
  if (auth.isAdmin) {
    return [
      { to: '/musteriler', name: 'tenants',  icon: ICONS.tenants,   labelKey: 'nav.tenants'  },
      { to: '/ayarlar',    name: 'settings', icon: ICONS.settings,  labelKey: 'nav.settings' },
    ]
  }
  return [
    { to: '/',             name: 'dashboard',   icon: ICONS.dashboard,   labelKey: 'nav.dashboard'   },
    { to: '/siparisler',   name: 'orders',      icon: ICONS.orders,      labelKey: 'nav.orders',      show: auth.tenant?.orders_enabled },
    { to: '/urunler',      name: 'products',    icon: ICONS.products,    labelKey: 'nav.products'    },
    { to: '/kategoriler',  name: 'categories',  icon: ICONS.categories,  labelKey: 'nav.categories'  },
    { to: '/masalar',      name: 'tables',      icon: ICONS.tables,      labelKey: 'nav.tables',      show: auth.tenant?.tables_enabled },
    { to: '/departmanlar', name: 'departments', icon: ICONS.departments, labelKey: 'nav.departments' },
    { to: '/yorumlar',     name: 'reviews',     icon: ICONS.reviews,     labelKey: 'nav.reviews'     },
    { to: '/ayarlar',      name: 'settings',    icon: ICONS.settings,    labelKey: 'nav.settings'    },
  ].filter(i => (i as any).show !== false)
})

function handleLogout() {
  auth.logout()
  router.push({ name: 'login' })
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; }

/* ══ CSS VARIABLES — DARK (default) ══ */
.lm-layout {
  --sb-bg:           #1e293b;
  --sb-border:       rgba(255,255,255,.06);
  --brand-name:      #f1f5f9;
  --tenant-name:     rgba(148,163,184,.6);
  --nav-text:        rgba(148,163,184,.85);
  --nav-hover-bg:    rgba(255,255,255,.07);
  --nav-hover-text:  #e2e8f0;
  --nav-act-bg:      rgba(249,115,22,.18);
  --nav-act-text:    #fb923c;
  --footer-border:   rgba(255,255,255,.06);
  --logout-text:     rgba(148,163,184,.6);
  --logout-hbg:      rgba(239,68,68,.1);
  --logout-htext:    #fca5a5;
  --mob-bg:          #1e293b;
  --mob-border:      rgba(255,255,255,.06);
  --mob-brand:       #f1f5f9;
  --main-bg:         #f1f5f9;
  --toggle-bg:       rgba(255,255,255,.08);
  --toggle-border:   rgba(255,255,255,.1);
  --toggle-color:    #94a3b8;
}

/* ══ CSS VARIABLES — LIGHT ══ */
.lm-layout.lm-light {
  --sb-bg:           #ffffff;
  --sb-border:       #e2e8f0;
  --brand-name:      #1e293b;
  --tenant-name:     #94a3b8;
  --nav-text:        #64748b;
  --nav-hover-bg:    #f1f5f9;
  --nav-hover-text:  #334155;
  --nav-act-bg:      #fff7ed;
  --nav-act-text:    #f97316;
  --footer-border:   #e2e8f0;
  --logout-text:     #94a3b8;
  --logout-hbg:      #fef2f2;
  --logout-htext:    #ef4444;
  --mob-bg:          #ffffff;
  --mob-border:      #e2e8f0;
  --mob-brand:       #1e293b;
  --main-bg:         #f8fafc;
  --toggle-bg:       #f1f5f9;
  --toggle-border:   #e2e8f0;
  --toggle-color:    #64748b;
}

/* ══ LAYOUT ══ */
.lm-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
  font-family: 'Nunito', 'Inter', system-ui, sans-serif;
}

/* ══ SIDEBAR ══ */
.lm-sidebar {
  width: 240px;
  flex-shrink: 0;
  display: none;
  flex-direction: column;
  background: var(--sb-bg);
  border-right: 1px solid var(--sb-border);
  overflow: hidden;
  transition: background .25s, border-color .25s;
}
@media (min-width: 768px) { .lm-sidebar { display: flex; } }

.lm-sidebar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px 14px;
  border-bottom: 1px solid var(--sb-border);
  flex-shrink: 0;
}
.lm-sidebar-icon {
  width: 34px; height: 34px; object-fit: contain; border-radius: 9px; flex-shrink: 0;
  filter: drop-shadow(0 2px 6px rgba(249,115,22,.3));
}
.lm-sidebar-brand { min-width: 0; flex: 1; }
.lm-brand-name {
  display: block; font-size: 13px; font-weight: 800;
  color: var(--brand-name); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.lm-tenant-name {
  display: block; font-size: 11px; color: var(--tenant-name);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ══ TOGGLE ══ */
.lm-toggle {
  display: flex; align-items: center; justify-content: center;
  width: 30px; height: 30px; flex-shrink: 0;
  background: var(--toggle-bg); border: 1px solid var(--toggle-border);
  border-radius: 8px; color: var(--toggle-color); cursor: pointer; transition: all .2s;
}
.lm-toggle:hover { background: rgba(249,115,22,.12); border-color: rgba(249,115,22,.3); color: #f97316; }
.lm-toggle-mobile { width: 34px; height: 34px; border-radius: 9px; }

/* ══ NAV ══ */
.lm-nav { flex: 1; padding: 10px 8px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
.lm-nav-item {
  display: flex; align-items: center; gap: 10px; padding: 9px 11px; border-radius: 10px;
  font-size: 13px; font-weight: 600; color: var(--nav-text); text-decoration: none;
  transition: background .15s, color .15s;
}
.lm-nav-item:hover { background: var(--nav-hover-bg); color: var(--nav-hover-text); }
.lm-nav-active { background: var(--nav-act-bg) !important; color: var(--nav-act-text) !important; }
.lm-nav-svg {
  width: 17px; height: 17px; flex-shrink: 0;
  transition: color .15s;
}
.lm-nav-label { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ══ SIDEBAR FOOTER ══ */
.lm-sidebar-footer {
  padding: 10px 8px; border-top: 1px solid var(--footer-border);
  display: flex; flex-direction: column; gap: 4px; flex-shrink: 0;
}
.lm-logout-btn {
  display: flex; align-items: center; gap: 10px; width: 100%;
  padding: 9px 11px; border-radius: 10px; font-size: 13px; font-weight: 600;
  color: var(--logout-text); background: none; border: none; cursor: pointer;
  transition: background .15s, color .15s; font-family: inherit; text-align: left;
}
.lm-logout-btn:hover { background: var(--logout-hbg); color: var(--logout-htext); }

/* ══ MOBILE BAR ══ */
.lm-mobile-bar {
  display: flex; align-items: center; justify-content: space-between;
  position: fixed; top: 0; left: 0; right: 0; z-index: 30;
  background: var(--mob-bg); border-bottom: 1px solid var(--mob-border);
  padding: 10px 14px; transition: background .25s, border-color .25s;
}
@media (min-width: 768px) { .lm-mobile-bar { display: none; } }
.lm-mobile-brand { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: var(--mob-brand); }
.lm-mobile-icon { width: 30px; height: 30px; object-fit: contain; border-radius: 8px; }
.lm-mobile-actions { display: flex; align-items: center; gap: 8px; }
.lm-hamburger {
  padding: 6px; background: var(--toggle-bg); border: 1px solid var(--toggle-border);
  border-radius: 8px; color: var(--toggle-color); cursor: pointer;
  display: flex; align-items: center; transition: all .2s;
}
.lm-hamburger:hover { background: rgba(249,115,22,.12); border-color: rgba(249,115,22,.3); color: #f97316; }

/* ══ MOBILE OVERLAY ══ */
.lm-mobile-overlay { position: fixed; inset: 0; z-index: 40; background: rgba(0,0,0,.55); }
.lm-mobile-menu {
  width: 240px; height: 100%; background: var(--sb-bg);
  padding: 16px 8px; display: flex; flex-direction: column; overflow-y: auto;
  transition: background .25s;
}
.lm-mobile-menu-header {
  display: flex; align-items: center; gap: 10px; padding: 4px 8px 16px;
  border-bottom: 1px solid var(--footer-border); margin-bottom: 10px;
}
.lm-overlay-enter-active, .lm-overlay-leave-active { transition: opacity .2s; }
.lm-overlay-enter-from, .lm-overlay-leave-to { opacity: 0; }

/* ══ MAIN ══ */
.lm-main {
  flex: 1; overflow-y: auto; background: var(--main-bg); transition: background .25s;
}
.lm-mobile-spacer { height: 52px; }
@media (min-width: 768px) { .lm-mobile-spacer { display: none; } }

/* ══ TOPBAR ══ */
.lm-topbar {
  display: none;
}
@media (min-width: 768px) {
  .lm-topbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 8px 20px;
    border-bottom: 1px solid var(--sb-border);
  }
}
</style>
