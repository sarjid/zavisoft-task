import { createRouter, createWebHistory } from 'vue-router'

import AdminLogin from '@/views/auth/AdminLogin.vue'
import PathNotFound from '@/views/PathNotFound.vue'

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior() {
    return { top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'admin-login',
      component: AdminLogin,
    },

    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: PathNotFound,
    },
  ],
})

export default router
