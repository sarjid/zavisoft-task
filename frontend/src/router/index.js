import { createRouter, createWebHistory } from 'vue-router'

import CartPage from '@/views/CartPage.vue'
import PathNotFound from '@/views/PathNotFound.vue'
import HomePage from '@/views/HomePage.vue'

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior() {
    return { top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomePage,
    },

    {
      path: '/cart',
      name: 'cart',
      component: CartPage,
    },

    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: PathNotFound,
    },
  ],
})

export default router
