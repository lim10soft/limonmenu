import { createRouter, createWebHistory } from 'vue-router'
import MenuPage from '../views/MenuPage.vue'
import OrderConfirmedPage from '../views/OrderConfirmedPage.vue'
import NotFoundPage from '../views/NotFoundPage.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/:slug',
      name: 'menu-simple',
      component: MenuPage,
    },
    {
      path: '/:slug/masa/:token',
      name: 'menu',
      component: MenuPage,
    },
    {
      path: '/:slug/dept/:deptToken',
      name: 'menu-dept',
      component: MenuPage,
    },
    {
      path: '/:slug/masa/:token/siparis/:orderId',
      name: 'order-confirmed',
      component: OrderConfirmedPage,
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: NotFoundPage,
    },
  ],
})

export default router
