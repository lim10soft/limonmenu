<template>
  <div v-if="store.loading" class="flex items-center justify-center min-h-screen" style="background:#faeee0">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-orange-500 border-t-transparent"></div>
  </div>

  <div v-else-if="store.error" class="flex flex-col items-center justify-center min-h-screen gap-3 p-8 text-center" style="background:#faeee0">
    <span class="text-5xl">😕</span>
    <p class="text-gray-600">{{ store.error }}</p>
  </div>

  <div v-else-if="store.tenant" style="background:#faeee0; min-height:100vh;">

    <!-- ── Ana Resim + Logo ── -->
    <div class="relative w-full" style="height:250px;">
      <img
        v-if="displayBanner"
        :src="displayBanner"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div
        v-else
        class="absolute inset-0"
        :style="`background:${store.tenant.theme_color || '#c0392b'};`"
      ></div>
      <!-- gradient -->
      <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.05) 50%, rgba(0,0,0,0.4) 100%);"></div>

      <!-- SAĞ ÜST: Dil seçici + Sepet -->
      <div class="absolute top-3 right-3 z-20 flex items-center gap-2">
        <button
          v-if="store.tenant.orders_enabled && store.cartCount > 0"
          @click="showCart = true"
          class="relative rounded-full p-2"
          style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.4 7H17" />
          </svg>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full flex items-center justify-center font-bold" style="width:16px;height:16px;font-size:9px;">{{ store.cartCount }}</span>
        </button>
        <LangSwitcher />
      </div>

      <!-- Merkezi yuvarlak logo -->
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="rounded-full overflow-hidden flex items-center justify-center" style="width:130px;height:130px;background:rgba(0,0,0,0.28);backdrop-filter:blur(2px);border:3px solid rgba(255,255,255,0.35);box-shadow:0 4px 28px rgba(0,0,0,0.45);">
          <img v-if="displayLogo" :src="displayLogo" class="w-full h-full object-cover" />
          <span v-else class="text-white font-bold text-3xl">{{ displayName[0] }}</span>
        </div>
      </div>

      <!-- Dept / Masa badge -->
      <div v-if="store.department || store.table" class="absolute bottom-3 left-3 flex gap-2">
        <span v-if="store.department" class="text-white text-xs px-3 py-1 rounded-full font-medium" style="background:rgba(0,0,0,0.5);">{{ store.department.name }}</span>
        <span v-if="store.table" class="text-white text-xs px-3 py-1 rounded-full font-medium" style="background:rgba(0,0,0,0.5);">{{ store.table.name }}</span>
      </div>

      <!-- Değerlendirme butonu: alt sol -->
      <div class="absolute bottom-3 left-3 flex flex-col items-start gap-1">
        <span class="text-white font-medium" style="font-size:10px; text-shadow:0 1px 4px rgba(0,0,0,0.7);">{{ t('review.rate_us') }}</span>
        <button
          @click="showReview = true"
          class="flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold"
          style="background:rgba(255,255,255,0.9); color:#1a1a1a;"
        >⭐ {{ t('review.rate_btn') }}</button>
      </div>
    </div>

    <!-- ── ÜST NAVBAR (scroll'da görünür) ── -->
    <Transition name="topbar">
      <div
        v-if="navVisible"
        class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-4"
        style="height:50px; background:rgba(22,19,15,0.94); backdrop-filter:blur(8px); border-bottom:1px solid rgba(255,255,255,0.07);"
      >
        <span class="text-white font-bold uppercase tracking-wider" style="font-size:14px; letter-spacing:0.12em;">{{ displayName }}</span>
        <LangSwitcher />
      </div>
    </Transition>

    <!-- ── Kategori Grid ── -->
    <div class="w-full px-2 pt-2" style="padding-bottom: 110px;">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px;">
        <template v-for="(item, i) in gridItems" :key="item.type === 'featured' ? 'featured' : item.data!.id">

          <!-- Önerilen & Kampanya Kartı -->
          <div
            v-if="item.type === 'featured'"
            @click="openFeatured"
            :style="{
              position: 'relative', overflow: 'hidden', borderRadius: '10px',
              height: '150px', cursor: 'pointer',
              gridColumn: i % 3 === 0 ? '1 / -1' : undefined,
              background: 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)'
            }"
          >
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.15);"></div>
            <!-- dekoratif çember -->
            <div style="position:absolute;top:-20px;right:-20px;width:110px;height:110px;border-radius:50%;background:rgba(255,255,255,0.12);"></div>
            <div style="position:absolute;bottom:-30px;left:-15px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:12px;">
              <span style="font-size:28px;line-height:1;">⭐</span>
              <span style="color:#fff;font-weight:800;text-transform:uppercase;text-align:center;font-size:13px;letter-spacing:0.1em;text-shadow:0 1px 6px rgba(0,0,0,0.6);line-height:1.3;">{{ t('menu.featured') }}</span>
              <span style="color:rgba(255,255,255,0.85);font-size:10px;font-weight:500;background:rgba(0,0,0,0.2);border-radius:20px;padding:2px 10px;">{{ store.featuredProducts.length }} ürün</span>
            </div>
          </div>

          <!-- Normal Kategori Kartı -->
          <div
            v-else
            @click="openCategory(item.data!)"
            :style="{
              position: 'relative', overflow: 'hidden', borderRadius: '10px',
              height: '150px', cursor: 'pointer',
              gridColumn: i % 3 === 0 ? '1 / -1' : undefined
            }"
          >
            <img v-if="item.data!.image" :src="item.data!.image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" />
            <div v-else style="position:absolute;inset:0;" :style="{ background: store.tenant?.theme_color || '#f97316' }"></div>
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.38);"></div>
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:0 8px;">
              <span style="color:#fff;font-weight:700;text-transform:uppercase;text-align:center;font-size:13px;letter-spacing:0.1em;text-shadow:0 1px 6px rgba(0,0,0,0.9);line-height:1.3;">{{ item.data!.name }}</span>
            </div>
          </div>

        </template>
      </div>
    </div>

    <!-- ── ÖNERİLEN PANEL ── -->
    <Transition name="panel">
      <div v-if="showFeatured" class="fixed inset-0 z-40" style="background:#faeee0;">

        <Transition name="topbar">
          <div v-if="featuredNavVisible" class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4" style="height:50px; background:rgba(22,19,15,0.94); backdrop-filter:blur(8px); border-bottom:1px solid rgba(255,255,255,0.07);">
            <button @click="closeFeatured" class="flex items-center gap-1 text-white font-semibold" style="font-size:13px; background:none; border:none; cursor:pointer; padding:0;">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
              {{ t('menu.backToMenu') }}
            </button>
            <LangSwitcher />
          </div>
        </Transition>

        <div ref="featuredScrollEl" class="absolute inset-0 overflow-y-auto" style="padding-bottom:110px;">

          <!-- Hero -->
          <div class="relative w-full" style="height:220px; background:linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);">
            <div style="position:absolute;top:-40px;right:-30px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.1);"></div>
            <div style="position:absolute;bottom:-50px;left:-20px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.07);"></div>
            <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.0) 50%, rgba(0,0,0,0.5) 100%);"></div>

            <button @click="closeFeatured" class="absolute top-3 left-3 z-10 flex items-center gap-1 rounded-full text-white font-semibold px-3 py-1.5" style="background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.2);font-size:12px;">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
              {{ t('menu.backToMenu') }}
            </button>

            <div class="absolute bottom-0 left-0 right-0 pb-3 flex justify-center">
              <span class="text-white font-bold uppercase px-5 py-1.5 rounded-full" style="background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);font-size:13px;letter-spacing:0.12em;">⭐ {{ t('menu.featured') }}</span>
            </div>
          </div>

          <!-- Ürünler -->
          <div class="w-full px-3 pt-3">
            <MenuProductCardLarge
              v-for="product in store.featuredProducts"
              :key="product.id"
              :product="product"
              :theme-color="store.tenant?.theme_color"
              :orders-enabled="store.tenant?.orders_enabled ?? false"
              @add="handleAdd"
            />
          </div>
        </div>

        <!-- Sepet butonu -->
        <div v-if="store.tenant?.orders_enabled && store.cartCount > 0" class="fixed inset-x-0 z-50 px-3" style="bottom:56px;">
          <div class="max-w-lg mx-auto">
            <button @click="showCart = true" class="w-full py-3 px-5 rounded-xl text-white font-bold flex items-center justify-between shadow-xl" :style="{ backgroundColor: store.tenant?.theme_color || '#f97316' }">
              <span class="bg-white/25 rounded-lg px-2 py-0.5 text-sm font-bold">{{ store.cartCount }}</span>
              <span>{{ t('cart.viewCart') }}</span>
              <span>{{ formatPrice(store.cartTotal) }}</span>
            </button>
          </div>
        </div>

        <!-- Alt navbar -->
        <div class="fixed bottom-0 left-0 right-0 z-50 flex items-center justify-between px-4" style="height:52px; background:rgba(22,19,15,0.92); backdrop-filter:blur(8px); border-top:1px solid rgba(255,255,255,0.06);">
          <img :src="'/limonlogo.png'" style="height:26px; width:auto; object-fit:contain;" alt="Limon POS" />
        </div>

      </div>
    </Transition>

    <!-- ── Sepet butonu (alt navbar'ın üstünde) ── -->
    <div
      v-if="store.tenant.orders_enabled && store.cartCount > 0 && !activeCategory"
      class="fixed inset-x-0 z-20 px-3"
      style="bottom: 56px;"
    >
      <div class="max-w-lg mx-auto">
        <button
          @click="showCart = true"
          class="w-full py-3 px-5 rounded-xl text-white font-bold flex items-center justify-between shadow-xl"
          :style="{ backgroundColor: store.tenant.theme_color || '#f97316' }"
        >
          <span class="bg-white/25 rounded-lg px-2 py-0.5 text-sm font-bold">{{ store.cartCount }}</span>
          <span>{{ t('cart.viewCart') }}</span>
          <span>{{ formatPrice(store.cartTotal) }}</span>
        </button>
      </div>
    </div>

    <!-- ── ALT NAVBAR ── -->
    <div class="fixed bottom-0 left-0 right-0 z-30 flex items-center justify-between px-4" style="height:52px; background:rgba(22,19,15,0.92); backdrop-filter:blur(8px); border-top:1px solid rgba(255,255,255,0.06);">
      <div class="flex items-center gap-2">
        <img :src="'/limonlogo.png'" style="height:26px; width:auto; object-fit:contain;" alt="Limon POS" />
      </div>
      <a
        v-if="store.tenant.instagram_url"
        :href="store.tenant.instagram_url"
        target="_blank"
        style="color:rgba(255,255,255,0.75); display:flex; align-items:center;"
        @click.stop
      >
        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
        </svg>
      </a>
      <div v-else style="width:22px;"></div>
    </div>

    <!-- ── ÜRÜN PANELİ ── -->
    <Transition name="panel">
      <div v-if="activeCategory" class="fixed inset-0 z-40" style="background:#faeee0;">

        <!-- Mini navbar: scroll'da fixed olarak çıkar -->
        <Transition name="topbar">
          <div
            v-if="panelNavVisible"
            class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4"
            style="height:50px; background:rgba(22,19,15,0.94); backdrop-filter:blur(8px); border-bottom:1px solid rgba(255,255,255,0.07);"
          >
            <button
              @click="closePanel"
              class="flex items-center gap-1 text-white font-semibold"
              style="font-size:13px; background:none; border:none; cursor:pointer; padding:0;"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
              {{ t('menu.backToMenu') }}
            </button>
            <LangSwitcher />
          </div>
        </Transition>

        <!-- Tek scroll container: hero + ürünler birlikte kayar -->
        <div ref="panelScrollEl" class="absolute inset-0 overflow-y-auto" style="padding-bottom:110px;">

          <!-- Hero (akışta, doğal kayar) -->
          <div class="relative w-full" style="height:220px;">
            <img v-if="activeCategory.image" :src="activeCategory.image" class="absolute inset-0 w-full h-full object-cover" />
            <div v-else class="absolute inset-0" :style="{ background: store.tenant?.theme_color || '#c0392b' }"></div>
            <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.05) 45%, rgba(0,0,0,0.65) 100%);"></div>

            <!-- Sol üst: Geri Dön -->
            <button
              @click="closePanel"
              class="absolute top-3 left-3 z-10 flex items-center gap-1 rounded-full text-white font-semibold px-3 py-1.5"
              style="background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.2);font-size:12px;"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
              {{ t('menu.backToMenu') }}
            </button>

            <!-- Sağ üst: dil seçici + sepet -->
            <div class="absolute top-3 right-3 z-10 flex items-center gap-2">
              <button
                v-if="store.tenant?.orders_enabled && store.cartCount > 0"
                @click="showCart = true"
                class="relative rounded-full p-2"
                style="background:rgba(255,255,255,0.2);backdrop-filter:blur(4px);"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.4 7H17" />
                </svg>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full flex items-center justify-center font-bold" style="width:16px;height:16px;font-size:9px;">{{ store.cartCount }}</span>
              </button>
              <LangSwitcher />
            </div>

            <!-- Merkez: Restoran logosu -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="rounded-full overflow-hidden flex items-center justify-center" style="width:105px;height:105px;background:rgba(0,0,0,0.28);backdrop-filter:blur(2px);border:3px solid rgba(255,255,255,0.35);box-shadow:0 4px 24px rgba(0,0,0,0.45);">
                <img v-if="displayLogo" :src="displayLogo" class="w-full h-full object-cover" />
                <span v-else class="text-white font-bold text-2xl">{{ displayName[0] }}</span>
              </div>
            </div>

            <!-- Alt: Kategori adı -->
            <div class="absolute bottom-0 left-0 right-0 pb-3 flex justify-center">
              <span class="text-white font-bold uppercase px-5 py-1.5 rounded-full" style="background:rgba(0,0,0,0.45);backdrop-filter:blur(4px);font-size:13px;letter-spacing:0.12em;">{{ activeCategory.name }}</span>
            </div>
          </div>

          <!-- Ürünler -->
          <div class="w-full px-3 pt-3">
            <template v-if="!activeCategory.children || activeCategory.children.length === 0">
              <MenuProductCardLarge
                v-for="product in activeCategory.products"
                :key="product.id"
                :product="product"
                :theme-color="store.tenant?.theme_color"
                :orders-enabled="store.tenant?.orders_enabled ?? false"
                @add="handleAdd"
              />
            </template>

            <template v-else>
              <MenuProductCardLarge
                v-for="product in activeCategory.products"
                :key="product.id"
                :product="product"
                :theme-color="store.tenant?.theme_color"
                :orders-enabled="store.tenant?.orders_enabled ?? false"
                @add="handleAdd"
              />
              <div v-for="sub in activeCategory.children" :key="sub.id" class="mt-4">
                <div class="flex items-center gap-2 mb-2 px-1">
                  <span class="h-4 w-1 rounded-full shrink-0" :style="{ background: store.tenant?.theme_color || '#f97316' }"></span>
                  <h3 class="font-bold text-gray-800 uppercase tracking-wide" style="font-size:12px;">{{ sub.name }}</h3>
                </div>
                <MenuProductCard
                  v-for="product in sub.products"
                  :key="product.id"
                  :product="product"
                  :theme-color="store.tenant?.theme_color"
                  :orders-enabled="store.tenant?.orders_enabled ?? false"
                  @add="handleAdd"
                />
              </div>
            </template>

            <!-- Değerlendirme bölümü -->
            <div class="relative mt-4 mb-2 rounded-2xl overflow-hidden" style="height:90px;">
              <img v-if="displayBanner" :src="displayBanner" class="absolute inset-0 w-full h-full object-cover" />
              <div v-else class="absolute inset-0" :style="{ background: store.tenant?.theme_color || '#f97316' }"></div>
              <div class="absolute inset-0" style="background:rgba(0,0,0,0.45);"></div>
              <div class="absolute bottom-3 left-3 flex flex-col items-start gap-1">
                <span class="text-white font-medium" style="font-size:10px; text-shadow:0 1px 4px rgba(0,0,0,0.7);">{{ t('review.rate_us') }}</span>
                <button
                  @click="showReview = true"
                  class="flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold"
                  style="background:rgba(255,255,255,0.9); color:#1a1a1a;"
                >⭐ {{ t('review.rate_btn') }}</button>
              </div>
            </div>

          </div>

        </div>

        <!-- Sepet butonu -->
        <div
          v-if="store.tenant?.orders_enabled && store.cartCount > 0"
          class="fixed inset-x-0 z-50 px-3"
          style="bottom:56px;"
        >
          <div class="max-w-lg mx-auto">
            <button
              @click="showCart = true"
              class="w-full py-3 px-5 rounded-xl text-white font-bold flex items-center justify-between shadow-xl"
              :style="{ backgroundColor: store.tenant?.theme_color || '#f97316' }"
            >
              <span class="bg-white/25 rounded-lg px-2 py-0.5 text-sm font-bold">{{ store.cartCount }}</span>
              <span>{{ t('cart.viewCart') }}</span>
              <span>{{ formatPrice(store.cartTotal) }}</span>
            </button>
          </div>
        </div>

        <!-- Alt navbar -->
        <div class="fixed bottom-0 left-0 right-0 z-50 flex items-center justify-between px-4" style="height:52px; background:rgba(22,19,15,0.92); backdrop-filter:blur(8px); border-top:1px solid rgba(255,255,255,0.06);">
          <div class="flex items-center gap-2">
            <img :src="'/limonlogo.png'" style="height:26px; width:auto; object-fit:contain;" alt="Limon POS" />
          </div>
          <a
            v-if="store.tenant?.instagram_url"
            :href="store.tenant.instagram_url"
            target="_blank"
            style="color:rgba(255,255,255,0.75); display:flex; align-items:center;"
            @click.stop
          >
            <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>
          <div v-else style="width:22px;"></div>
        </div>
      </div>
    </Transition>

    <CartDrawer v-if="showCart" @close="showCart = false" @ordered="onOrdered" />
    <ReviewModal v-if="showReview" :slug="(route.params.slug as string)" :theme-color="store.tenant?.theme_color" @close="showReview = false" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useMenuStore } from '../stores/menuStore'
import type { Category, Product } from '@/types'
import { formatPrice as _formatPrice } from '@/utils/currency'
import { setLanguage } from '@/i18n'
import CartDrawer from '../components/CartDrawer.vue'
import LangSwitcher from '../components/LangSwitcher.vue'
import MenuProductCard from '../components/MenuProductCard.vue'
import MenuProductCardLarge from '../components/MenuProductCardLarge.vue'
import ReviewModal from '../components/ReviewModal.vue'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const store = useMenuStore()

const displayLogo   = computed(() => store.department?.logo         || store.tenant?.logo         || null)
const displayName   = computed(() => store.department?.display_name || store.tenant?.name         || '')
const displayBanner = computed(() => store.department?.banner_image || store.tenant?.banner_image || null)

const showCart = ref(false)
const showReview = ref(false)
const showFeatured = ref(false)
const activeCategory = ref<Category | null>(null)
const navVisible = ref(false)
const panelNavVisible = ref(false)
const featuredNavVisible = ref(false)
const panelScrollEl = ref<HTMLElement | null>(null)
const featuredScrollEl = ref<HTMLElement | null>(null)
let rafId = 0

type GridItem = { type: 'category'; data: Category } | { type: 'featured'; data: null }
const gridItems = computed<GridItem[]>(() => {
  const cats: GridItem[] = store.categories.map(c => ({ type: 'category', data: c }))
  if (store.featuredProducts.length === 0) return cats
  const result = [...cats]
  result.splice(2, 0, { type: 'featured', data: null })
  return result
})

watch(panelScrollEl, (el, _, onCleanup) => {
  if (!el) return
  const handler = () => { panelNavVisible.value = el.scrollTop > 50 }
  el.addEventListener('scroll', handler, { passive: true })
  onCleanup(() => el.removeEventListener('scroll', handler))
})

watch(featuredScrollEl, (el, _, onCleanup) => {
  if (!el) return
  const handler = () => { featuredNavVisible.value = el.scrollTop > 50 }
  el.addEventListener('scroll', handler, { passive: true })
  onCleanup(() => el.removeEventListener('scroll', handler))
})

watch(() => store.categories, (newCats) => {
  if (!activeCategory.value) return
  const fresh = newCats.find(c => c.id === activeCategory.value!.id)
  if (fresh) activeCategory.value = fresh
})

function scrollLoop() {
  const y = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0
  navVisible.value = y > 1
  rafId = requestAnimationFrame(scrollLoop)
}

onMounted(async () => {
  const slug = route.params.slug as string
  const token = route.params.token as string | undefined
  const deptToken = route.params.deptToken as string | undefined
  const lang = route.query.lang as string | undefined
  if (lang) setLanguage(lang)
  await store.loadMenu(slug, token || undefined, lang || undefined, deptToken || undefined)
  if (!lang && store.currentLang) {
    router.replace({ query: { ...route.query, lang: store.currentLang } })
  }
  rafId = requestAnimationFrame(scrollLoop)
})

onUnmounted(() => {
  document.body.style.overflow = ''
  cancelAnimationFrame(rafId)
})

function openCategory(cat: Category) {
  activeCategory.value = cat
  document.body.style.overflow = 'hidden'
}

function openFeatured() {
  showFeatured.value = true
  document.body.style.overflow = 'hidden'
}

function closeFeatured() {
  showFeatured.value = false
  featuredNavVisible.value = false
  document.body.style.overflow = ''
}

function closePanel() {
  activeCategory.value = null
  panelNavVisible.value = false
  document.body.style.overflow = ''
}

interface AddEvent { product: Product; unitLabel?: string; unitPrice?: number }

function handleAdd({ product, unitLabel, unitPrice }: AddEvent) {
  const p = unitPrice != null ? { ...product, price: unitPrice } : product
  store.addToCart(p, 1, unitLabel ?? '')
}

function formatPrice(amount: number) {
  return _formatPrice(amount, store.tenant?.currency_code ?? 'TRY')
}

function onOrdered(orderId: number) {
  showCart.value = false
  closePanel()
  router.push({ name: 'order-confirmed', params: { slug: route.params.slug, token: route.params.token, orderId } })
}
</script>

<style scoped>
.topbar-enter-active,
.topbar-leave-active {
  transition: transform 0.22s ease, opacity 0.22s ease;
}
.topbar-enter-from,
.topbar-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

.panel-enter-active,
.panel-leave-active {
  transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}
.panel-enter-from,
.panel-leave-to {
  transform: translateX(100%);
}
</style>
