import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHistory('/panel'),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginPage.vue'),
      meta: { guest: true },
    },
    {
      path: '/',
      component: () => import('../layouts/DashboardLayout.vue'),
      meta: { auth: true },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: () => import('../views/DashboardPage.vue'),
        },
        {
          path: 'urunler',
          name: 'products',
          component: () => import('../views/ProductsPage.vue'),
        },
        {
          path: 'kategoriler',
          name: 'categories',
          component: () => import('../views/CategoriesPage.vue'),
        },
        {
          path: 'masalar',
          name: 'tables',
          component: () => import('../views/TablesPage.vue'),
        },
        {
          path: 'siparisler',
          name: 'orders',
          component: () => import('../views/OrdersPage.vue'),
        },
        {
          path: 'departmanlar',
          name: 'departments',
          component: () => import('../views/DepartmentsPage.vue'),
        },
        {
          path: 'yorumlar',
          name: 'reviews',
          component: () => import('../views/ReviewsPage.vue'),
        },
        {
          path: 'ayarlar',
          name: 'settings',
          component: () => import('../views/SettingsPage.vue'),
        },
        {
          path: 'musteriler',
          name: 'tenants',
          component: () => import('../views/TenantsPage.vue'),
        },
      ],
    },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.auth && !auth.token) return { name: 'login' }
  if (to.meta.guest && auth.token) return { name: 'dashboard' }
})

export default router
