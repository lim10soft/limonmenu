<template>
  <div class="p-4 md:p-6 max-w-5xl mx-auto">
    <!-- Başlık -->
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ t('products.title') }}</h1>
        <p v-if="total > 0" class="text-xs text-gray-400 mt-0.5">{{ total }} {{ t('products.total_count') }}</p>
      </div>
      <div class="flex items-center gap-2">
        <button
          @click="autoTranslate"
          :disabled="translating"
          class="hidden sm:flex px-3 py-2 border border-blue-200 text-blue-600 rounded-xl text-sm font-medium hover:bg-blue-50 disabled:opacity-60 transition-colors items-center gap-1.5"
        >
          <svg v-if="translating" class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
          </svg>
          <span>{{ translating ? t('products.translating') : t('products.auto_translate') }}</span>
        </button>
        <button @click="openModal()" class="px-3 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors flex items-center gap-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          <span class="hidden sm:inline">{{ t('products.add') }}</span>
        </button>
      </div>
    </div>

    <!-- Arama -->
    <div class="mb-4 relative">
      <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <input
        v-model="searchQuery"
        type="text"
        :placeholder="t('products.search_placeholder')"
        class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white"
      />
      <button v-if="searchQuery" @click="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Yükleniyor -->
    <div v-if="loading" class="flex justify-center py-16">
      <div class="animate-spin h-8 w-8 border-4 border-orange-500 border-t-transparent rounded-full"></div>
    </div>

    <template v-else>
      <!-- Boş durum -->
      <div v-if="products.length === 0" class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
          </svg>
        </div>
        <p class="text-gray-500 text-sm">{{ searchQuery ? t('products.no_results') : t('products.empty') }}</p>
        <button v-if="!searchQuery" @click="openModal()" class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors">
          {{ t('products.add') }}
        </button>
      </div>

      <template v-else>
        <!-- MOBİL: Kart listesi -->
        <div class="md:hidden space-y-2">
          <div
            v-for="p in products"
            :key="p.id"
            class="bg-white rounded-2xl shadow-sm p-4"
          >
            <div class="flex items-start gap-3">
              <img v-if="p.image" :src="p.image" class="w-14 h-14 rounded-xl object-cover shrink-0" />
              <div v-else class="w-14 h-14 rounded-xl bg-gray-100 shrink-0 flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <span class="font-semibold text-gray-800 leading-tight">{{ p.name }}</span>
                  <span class="font-bold text-orange-600 shrink-0 text-sm">{{ formatPrice(p.price) }}</span>
                </div>
                <p v-if="p.category?.name" class="text-xs text-gray-400 mt-0.5">{{ p.category.name }}</p>
                <!-- Alt satır: toggle'lar + butonlar -->
                <div class="flex items-center justify-between mt-3 gap-2">
                  <div class="flex items-center gap-3">
                    <div class="flex flex-col items-center gap-0.5">
                      <button @click="toggleActive(p)" class="relative w-9 h-5 rounded-full transition-colors" :class="p.active ? 'bg-green-500' : 'bg-gray-300'" :title="p.active ? 'Aktif' : 'Pasif'">
                        <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="p.active ? 'right-0.5' : 'left-0.5'"></span>
                      </button>
                      <span class="text-[10px]" :class="p.active ? 'text-green-500' : 'text-gray-400'">{{ p.active ? 'Aktif' : 'Pasif' }}</span>
                    </div>
                    <div class="flex flex-col items-center gap-0.5">
                      <button @click="toggleInStock(p)" class="relative w-9 h-5 rounded-full transition-colors" :class="p.in_stock ? 'bg-blue-500' : 'bg-red-400'" :title="p.in_stock ? 'Stokta' : 'Tükendi'">
                        <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="p.in_stock ? 'right-0.5' : 'left-0.5'"></span>
                      </button>
                      <span class="text-[10px]" :class="p.in_stock ? 'text-blue-500' : 'text-red-400'">{{ p.in_stock ? 'Stokta' : 'Tükendi' }}</span>
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <button @click="openModal(p)" class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg text-xs font-medium hover:bg-orange-100 transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                      Düzenle
                    </button>
                    <button @click="deleteProduct(p.id)" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-500 rounded-lg text-xs font-medium hover:bg-red-100 transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                      Sil
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MASAÜSTÜ: Tablo -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-100">
                <th class="text-left px-4 py-3 text-gray-500 font-medium">{{ t('common.name') }}</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">{{ t('common.category') }}</th>
                <th class="text-right px-4 py-3 text-gray-500 font-medium">{{ t('common.price') }}</th>
                <th class="text-center px-4 py-3 text-gray-500 font-medium">{{ t('common.status') }}</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="p in products" :key="p.id" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <img v-if="p.image" :src="p.image" class="w-10 h-10 rounded-lg object-cover shrink-0" />
                    <div v-else class="w-10 h-10 rounded-lg bg-gray-100 shrink-0"></div>
                    <span class="font-medium text-gray-800">{{ p.name }}</span>
                  </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ p.category?.name }}</td>
                <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ formatPrice(p.price) }}</td>
                <td class="px-4 py-3 text-center">
                  <div class="flex flex-col items-center gap-1.5">
                    <button @click="toggleActive(p)" class="relative w-10 h-5 rounded-full transition-colors" :class="p.active ? 'bg-green-500' : 'bg-gray-300'" :title="p.active ? 'Aktif' : 'Pasif'">
                      <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="p.active ? 'right-0.5' : 'left-0.5'"></span>
                    </button>
                    <button @click="toggleInStock(p)" class="relative w-10 h-5 rounded-full transition-colors" :class="p.in_stock ? 'bg-blue-500' : 'bg-red-400'" :title="p.in_stock ? 'Stokta var' : 'Tükendi'">
                      <span class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="p.in_stock ? 'right-0.5' : 'left-0.5'"></span>
                    </button>
                    <span class="text-xs" :class="p.in_stock ? 'text-blue-500' : 'text-red-400'">{{ p.in_stock ? 'Stokta' : 'Tükendi' }}</span>
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="flex justify-end gap-1">
                    <button @click="openModal(p)" class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors" title="Düzenle">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                    </button>
                    <button @click="deleteProduct(p.id)" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Sil">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sayfalama -->
        <div v-if="lastPage > 1" class="flex items-center justify-between mt-4 px-1">
          <span class="text-xs text-gray-400">{{ total }} üründen {{ (currentPage - 1) * 20 + 1 }}–{{ Math.min(currentPage * 20, total) }} gösteriliyor</span>
          <div class="flex items-center gap-1">
            <button
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-sm transition-colors disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-100 text-gray-600"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </button>
            <template v-for="page in visiblePages" :key="page">
              <span v-if="page === '...'" class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">…</span>
              <button
                v-else
                @click="goToPage(page as number)"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
                :class="page === currentPage ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
              >{{ page }}</button>
            </template>
            <button
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === lastPage"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-sm transition-colors disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-100 text-gray-600"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </button>
          </div>
        </div>
      </template>
    </template>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 p-0 sm:p-4">
      <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-lg p-6 shadow-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-bold text-lg">{{ editingProduct ? t('products.edit_title') : t('products.add_title') }}</h2>
          <button @click="showModal = false" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('products.name') }}</label>
            <input v-model="form.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('products.description') }}</label>
            <textarea v-model="form.description" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('products.category') }}</label>
            <select v-model="form.category_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
              <option v-for="cat in flatCategories" :key="cat.id" :value="cat.id">{{ cat.indent ? '↳ ' : '' }}{{ cat.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('products.price') }}</label>
            <input v-model.number="form.price" type="number" min="0" step="0.01" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('products.image_url') }}</label>
            <ImageUploader v-model="form.image" type="products" />
          </div>

          <!-- Stok Durumu -->
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p class="text-sm font-medium text-gray-700">Stok Durumu</p>
              <p class="text-xs text-gray-400">Kapalı olduğunda müşterilere "Tükendi" gösterilir</p>
            </div>
            <button type="button" @click="form.in_stock = !form.in_stock" class="relative w-11 h-6 rounded-full transition-colors" :class="form.in_stock ? 'bg-blue-500' : 'bg-red-400'">
              <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="form.in_stock ? 'right-1' : 'left-1'"></span>
            </button>
          </div>

          <!-- Önerilen Ürün -->
          <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl border border-amber-100">
            <div>
              <p class="text-sm font-medium text-gray-700">⭐ {{ t('products.featured_label') }}</p>
              <p class="text-xs text-gray-400">{{ t('products.featured_desc') }}</p>
            </div>
            <button type="button" @click="form.is_featured = !form.is_featured" class="relative w-11 h-6 rounded-full transition-colors" :class="form.is_featured ? 'bg-amber-400' : 'bg-gray-300'">
              <span class="absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-transform" :class="form.is_featured ? 'right-1' : 'left-1'"></span>
            </button>
          </div>

          <!-- Besin ve Yasal Uyumluluk -->
          <div class="border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
              <p class="text-sm font-medium text-gray-700">Besin Bilgileri & Yasal Uyumluluk</p>
            </div>
            <div class="p-3 space-y-3">
              <div>
                <label class="block text-xs text-gray-500 mb-1">Kalori (kcal)</label>
                <input v-model.number="form.calories" type="number" min="0" placeholder="örn. 450" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-orange-300" />
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-1">İçindekiler</label>
                <textarea v-model="form.ingredients" rows="2" placeholder="Buğday unu, su, tuz..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-orange-300 resize-none"></textarea>
              </div>
              <div>
                <label class="block text-xs text-gray-500 mb-2">Alerjenler</label>
                <div class="grid grid-cols-2 gap-2">
                  <label v-for="al in ALLERGENS" :key="al.key" class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" :value="al.key" v-model="form.allergens" class="rounded border-gray-300 text-orange-500 focus:ring-orange-300" />
                    <span>{{ al.icon }} {{ al.label }}</span>
                  </label>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2 pt-1">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" v-model="form.is_vegan" class="rounded border-gray-300 text-green-500 focus:ring-green-300" />
                  <span>🌱 Vegan</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" v-model="form.is_vegetarian" class="rounded border-gray-300 text-green-500 focus:ring-green-300" />
                  <span>🥦 Vejetaryen</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" v-model="form.has_alcohol" class="rounded border-gray-300 text-red-500 focus:ring-red-300" />
                  <span>🍺 Alkol İçerir</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" v-model="form.has_pork" class="rounded border-gray-300 text-red-500 focus:ring-red-300" />
                  <span>🐷 Domuz İçerir</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Departman Fiyatları -->
          <div v-if="departments.length > 0">
            <label class="block text-sm font-medium text-gray-700 mb-2">Departman Fiyatları</label>
            <div class="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100">
              <div v-for="dp in form.department_prices" :key="dp.department_id" class="flex items-center gap-3 px-3 py-2">
                <span class="flex-1 text-sm text-gray-700 truncate">{{ dp.name }}</span>
                <input v-model.number="dp.price" type="number" min="0" step="0.01" placeholder="Varsayılan fiyat" class="w-32 text-sm border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-orange-300" />
              </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">Boş bırakılan departmanlar varsayılan fiyatı kullanır.</p>
          </div>

          <!-- Birimler -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-sm font-medium text-gray-700">Birimler <span class="text-gray-400 font-normal text-xs">(opsiyonel — Küçük/Orta/Büyük gibi)</span></label>
              <button @click="addUnit" type="button" class="text-xs text-orange-600 hover:text-orange-700 font-medium">+ Ekle</button>
            </div>
            <div v-if="form.units.length > 0" class="border border-gray-200 rounded-xl divide-y divide-gray-100">
              <div v-for="(unit, i) in form.units" :key="i" class="flex items-center gap-2 px-3 py-2">
                <input v-model="unit.label" type="text" placeholder="Etiket (Küçük, Büyük...)" class="flex-1 text-sm border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-orange-300" />
                <input v-model.number="unit.price" type="number" min="0" step="0.01" placeholder="Fiyat" class="w-24 text-sm border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-orange-300" />
                <button @click="removeUnit(i)" type="button" class="text-red-400 hover:text-red-600 p-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <p v-if="form.units.length > 0" class="text-xs text-gray-400 mt-1">Birim girildiğinde müşteri menüsünde ürün fiyatı yerine birimler gösterilir.</p>
          </div>

          <!-- Çeviri sekmeleri -->
          <div v-if="activeLangs.length > 0">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('products.translations_label') }}</label>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
              <div class="flex border-b border-gray-200 overflow-x-auto">
                <button
                  v-for="lang in activeLangs"
                  :key="lang.lang_code"
                  @click="activeTab = lang.lang_code"
                  class="px-3 py-2 text-xs font-medium whitespace-nowrap transition-colors"
                  :class="activeTab === lang.lang_code ? 'bg-orange-50 text-orange-600 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                >
                  {{ getLangMeta(lang.lang_code)?.flag }} {{ lang.lang_code.toUpperCase() }}
                </button>
              </div>
              <div class="p-3 space-y-3">
                <div v-for="lang in activeLangs" :key="lang.lang_code" v-show="activeTab === lang.lang_code">
                  <label class="block text-xs text-gray-500 mb-1">{{ getLangMeta(lang.lang_code)?.label }} — {{ t('common.name') }}</label>
                  <input v-model="form.translations[lang.lang_code].name" type="text" :placeholder="`${form.name || t('products.name')} (${lang.lang_code})`" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 mb-2" />
                  <label class="block text-xs text-gray-500 mb-1">{{ getLangMeta(lang.lang_code)?.label }} — {{ t('common.description') }}</label>
                  <textarea v-model="form.translations[lang.lang_code].description" rows="2" :placeholder="`${t('common.description')} (${lang.lang_code})`" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-3 mt-6">
          <button @click="showModal = false" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition-colors">{{ t('common.cancel') }}</button>
          <button @click="saveProduct" :disabled="saving" class="flex-1 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-semibold disabled:opacity-60 hover:bg-orange-600 transition-colors">
            {{ saving ? t('common.saving') : t('common.save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Product, Category, TenantLanguage, Department } from '@/types'
import { ADMIN_LANGUAGES } from '../i18n'
import http from '@/api/http'
import { useToast } from '../composables/useToast'
import { useConfirm } from '../composables/useConfirm'
import ImageUploader from '../components/ImageUploader.vue'

const { t } = useI18n()
const { success, error: toastError } = useToast()
const { confirm } = useConfirm()

const products = ref<Product[]>([])
const categories = ref<Category[]>([])
const activeLangs = ref<TenantLanguage[]>([])
const departments = ref<Department[]>([])

interface FlatCategory { id: number; name: string; indent: boolean }
const flatCategories = computed<FlatCategory[]>(() => {
  const result: FlatCategory[] = []
  for (const cat of categories.value) {
    result.push({ id: cat.id, name: cat.name, indent: false })
    for (const child of cat.children ?? []) {
      result.push({ id: child.id, name: child.name, indent: true })
    }
  }
  return result
})

const searchQuery = ref('')
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)

const visiblePages = computed<(number | string)[]>(() => {
  const pages: (number | string)[] = []
  const lp = lastPage.value
  const cp = currentPage.value
  if (lp <= 7) {
    for (let i = 1; i <= lp; i++) pages.push(i)
  } else {
    pages.push(1)
    if (cp > 3) pages.push('...')
    for (let i = Math.max(2, cp - 1); i <= Math.min(lp - 1, cp + 1); i++) pages.push(i)
    if (cp < lp - 2) pages.push('...')
    pages.push(lp)
  }
  return pages
})

const loading = ref(true)
const showModal = ref(false)
const saving = ref(false)
const translating = ref(false)
const editingProduct = ref<Product | null>(null)
const activeTab = ref('')

interface DeptPrice { department_id: number; name: string; price: number | null }
interface UnitRow { label: string; price: number; sort_order: number }

const ALLERGENS = [
  { key: 'gluten',   icon: '🌾', label: 'Gluten' },
  { key: 'milk',     icon: '🥛', label: 'Süt/Laktoz' },
  { key: 'egg',      icon: '🥚', label: 'Yumurta' },
  { key: 'peanut',   icon: '🥜', label: 'Yer Fıstığı' },
  { key: 'nuts',     icon: '🌰', label: 'Kabuklu Yemiş' },
  { key: 'soy',      icon: '🫘', label: 'Soya' },
  { key: 'sesame',   icon: '🌿', label: 'Susam' },
  { key: 'seafood',  icon: '🐟', label: 'Balık/Deniz Ürünleri' },
]

const form = ref({
  name: '', category_id: 0, description: '', price: 0, image: '',
  in_stock: true,
  calories: null as number | null,
  ingredients: '',
  allergens: [] as string[],
  is_vegan: false,
  is_vegetarian: false,
  has_alcohol: false,
  has_pork: false,
  is_featured: false,
  translations: {} as Record<string, { name: string; description: string }>,
  department_prices: [] as DeptPrice[],
  units: [] as UnitRow[],
})

async function loadProducts() {
  loading.value = true
  const params: Record<string, unknown> = { page: currentPage.value }
  if (searchQuery.value.trim()) params.search = searchQuery.value.trim()
  const res = await http.get('/admin/products', { params })
  products.value = res.data.data
  lastPage.value = res.data.meta.last_page
  total.value = res.data.meta.total
  loading.value = false
}

function goToPage(page: number) {
  if (page < 1 || page > lastPage.value) return
  currentPage.value = page
  loadProducts()
}

function clearSearch() {
  searchQuery.value = ''
}

let searchTimer: ReturnType<typeof setTimeout>
watch(searchQuery, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    currentPage.value = 1
    loadProducts()
  }, 350)
})

function getLangMeta(code: string) {
  return ADMIN_LANGUAGES.find((l) => l.code === code)
}

function buildTranslations() {
  const tr: Record<string, { name: string; description: string }> = {}
  activeLangs.value.forEach((l) => { tr[l.lang_code] = { name: '', description: '' } })
  return tr
}

onMounted(async () => {
  const [cRes, lRes, dRes] = await Promise.all([
    http.get('/admin/categories'),
    http.get('/admin/languages'),
    http.get('/admin/departments'),
  ])
  categories.value = cRes.data.data
  activeLangs.value = (lRes.data.data as TenantLanguage[]).filter((l) => l.active)
  departments.value = dRes.data.data
  if (activeLangs.value.length) activeTab.value = activeLangs.value[0].lang_code
  await loadProducts()
})

function openModal(p?: Product) {
  editingProduct.value = p ?? null
  const translations = buildTranslations()
  if (p?.translations) {
    activeLangs.value.forEach((l) => {
      translations[l.lang_code] = {
        name: p.translations?.[l.lang_code]?.name ?? '',
        description: p.translations?.[l.lang_code]?.description ?? '',
      }
    })
  }
  const existingPrices: Record<number, number | null> = {}
  if (p?.department_prices) {
    for (const dp of p.department_prices as { department_id: number; price: number | null }[]) {
      existingPrices[dp.department_id] = dp.price
    }
  }
  const department_prices: DeptPrice[] = departments.value.map((d) => ({
    department_id: d.id,
    name: d.name,
    price: existingPrices[d.id] ?? null,
  }))
  form.value = {
    name: p?.name ?? '',
    category_id: p?.category_id ?? flatCategories.value[0]?.id ?? 0,
    description: p?.description ?? '',
    price: p?.price ?? 0,
    image: p?.image ?? '',
    in_stock: p?.in_stock ?? true,
    calories: p?.calories ?? null,
    ingredients: p?.ingredients ?? '',
    allergens: p?.allergens ? [...p.allergens] : [],
    is_vegan: p?.is_vegan ?? false,
    is_vegetarian: p?.is_vegetarian ?? false,
    has_alcohol: p?.has_alcohol ?? false,
    has_pork: p?.has_pork ?? false,
    is_featured: p?.is_featured ?? false,
    translations,
    department_prices,
    units: (p?.units ?? []).map((u, i) => ({ label: u.label ?? '', price: u.price, sort_order: u.sort_order ?? i })),
  }
  if (activeLangs.value.length) activeTab.value = activeLangs.value[0].lang_code
  showModal.value = true
}

function addUnit() {
  form.value.units.push({ label: '', price: 0, sort_order: form.value.units.length })
}

function removeUnit(i: number) {
  form.value.units.splice(i, 1)
}

async function saveProduct() {
  saving.value = true
  try {
    const payload = {
      name: form.value.name,
      category_id: form.value.category_id,
      description: form.value.description || null,
      price: form.value.price,
      image: form.value.image || null,
      in_stock: form.value.in_stock,
      calories: form.value.calories ?? null,
      ingredients: form.value.ingredients || null,
      allergens: form.value.allergens,
      is_vegan: form.value.is_vegan,
      is_vegetarian: form.value.is_vegetarian,
      has_alcohol: form.value.has_alcohol,
      has_pork: form.value.has_pork,
      is_featured: form.value.is_featured,
      translations: Object.entries(form.value.translations).map(([lang_code, tr]) => ({
        lang_code, name: tr.name, description: tr.description || null,
      })),
      department_prices: form.value.department_prices
        .filter((dp) => dp.price !== null && dp.price !== undefined)
        .map((dp) => ({ department_id: dp.department_id, price: dp.price })),
      units: form.value.units.map((u, i) => ({ label: u.label || null, price: u.price, sort_order: i })),
    }
    if (editingProduct.value) {
      await http.put(`/admin/products/${editingProduct.value.id}`, payload)
    } else {
      await http.post('/admin/products', payload)
    }
    showModal.value = false
    await loadProducts()
  } finally {
    saving.value = false
  }
}

async function autoTranslate() {
  if (!await confirm(t('products.auto_translate_confirm'), { title: t('products.auto_translate'), confirmText: 'Evet', type: 'warning' })) return
  translating.value = true
  try {
    const res = await http.post('/admin/products/auto-translate')
    const { count, langs } = res.data
    success(t('products.auto_translate_done', { count, langs }))
    await loadProducts()
  } catch {
    toastError(t('products.auto_translate_error'))
  } finally {
    translating.value = false
  }
}

async function toggleActive(p: Product) {
  await http.patch(`/admin/products/${p.id}`, { active: !p.active })
  p.active = !p.active
}

async function toggleInStock(p: Product) {
  await http.patch(`/admin/products/${p.id}`, { in_stock: !p.in_stock })
  p.in_stock = !p.in_stock
}

async function deleteProduct(id: number) {
  if (!await confirm(t('products.confirm_delete'), { title: t('products.title'), confirmText: t('common.delete'), type: 'danger' })) return
  await http.delete(`/admin/products/${id}`)
  await loadProducts()
}

function formatPrice(v: number) {
  return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(v)
}
</script>
