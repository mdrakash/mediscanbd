import { createRouter, createWebHistory } from 'vue-router'

// Import layouts
import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'

// Import pages
import LoginPage from '../pages/LoginPage.vue'
import CallbackPage from '../pages/CallbackPage.vue'
import DashboardPage from '../pages/DashboardPage.vue'
import UploadPage from '../pages/UploadPage.vue'
import AnalysisResultPage from '../pages/AnalysisResultPage.vue'
import HistoryPage from '../pages/HistoryPage.vue'
import NotFoundPage from '../pages/NotFoundPage.vue'

const routes = [
  {
    path: '/',
    redirect: () => {
      const token = localStorage.getItem('mediscan_token')
      return token ? '/dashboard' : '/login'
    }
  },
  // Auth routes (with AuthLayout)
  {
    path: '/',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'Login',
        component: LoginPage,
        meta: { guest: true }
      },
      {
        path: 'auth/callback',
        name: 'AuthCallback',
        component: CallbackPage,
        meta: { guest: true }
      }
    ]
  },
  // App routes (with AppLayout)
  {
    path: '/',
    component: AppLayout,
    children: [
      {
        path: 'dashboard',
        name: 'Dashboard',
        component: DashboardPage,
        meta: { requiresAuth: true }
      },
      {
        path: 'upload',
        name: 'Upload',
        component: UploadPage,
        meta: { requiresAuth: true }
      },
      {
        path: 'analysis/:id',
        name: 'AnalysisResult',
        component: AnalysisResultPage,
        meta: { requiresAuth: true }
      },
      {
        path: 'history',
        name: 'History',
        component: HistoryPage,
        meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFoundPage
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach((to, from) => {
  const token = localStorage.getItem('mediscan_token')
  const isAuthenticated = !!token

  // Redirect to dashboard if already logged in and trying to access guest pages
  if (to.meta.guest && isAuthenticated) {
    return { name: 'Dashboard' }
  }

  // Redirect to login if not authenticated and trying to access protected routes
  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'Login' }
  }

  return true // Allow navigation
})

export default router
