import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/Homepage.vue'
import Loginform from '@/pages/loginform.vue'
import Signupform from '@/pages/signupform.vue'
import UserDashboard from '@/User/components/UserDashboard.vue'
import Userhomepage from '@/User/components/layouts/Userhomepage.vue'
import Adminlogin from '@/pages/adminlogin.vue'
import AdminDashboard from '@/Admin/components/AdminDashboard.vue'
import AdminPanel from '@/Admin/components/layouts/AdminPanel.vue'

const routes = [
  // Public routes
  { 
    path: '/', 
    name: 'homepage', 
    component: Homepage
  }, 
  { 
    path: '/login', 
    name: 'loginform', 
    component: Loginform
  },
  { 
    path: '/signup', 
    name: 'signupform', 
    component: Signupform
  },
  { 
    path: '/register', 
    redirect: '/signup'  // Redirect /register to /signup
  },
  { 
    path: '/adminlogin', 
    name: 'adminlogin', 
    component: Adminlogin
  },

  // User routes - ALL wrapped in UserDashboard
  {
    path: '/userdashboard',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'userdashboard', 
        component: Userhomepage
      }
    ]
  },
  {
    path: '/profile',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'profile', 
        component: () => import('@/User/views/Profile.vue')
      }
    ]
  },
  {
    path: '/cart',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'cart', 
        component: () => import('@/User/views/Cart.vue')
      }
    ]
  },
  {
    path: '/wishlist',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'wishlist', 
        component: () => import('@/User/views/Wishlist.vue')
      }
    ]
  },
  {
    path: '/favourite',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'favourite', 
        component: () => import('@/User/views/Favourite.vue')
      }
    ]
  },
  {
    path: '/myorders',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'myorders', 
        component: () => import('@/User/views/MyOrders.vue')
      }
    ]
  },
  {
    path: '/about',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'about', 
        component: () => import('@/User/pages/about.vue')
      }
    ]
  },
  {
    path: '/privacy',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'privacy', 
        component: () => import('@/User/pages/privacy.vue')
      }
    ]
  },
  {
    path: '/support',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'support', 
        component: () => import('@/User/pages/support.vue')
      }
    ]
  },
  // Admin Routes
 {
  path: '/admindashboard',
  component: AdminDashboard,
  meta: { requiresAdminAuth: true },
  children: [
    { 
      path: '', 
      name: 'admindashboard', 
      component: AdminPanel
    }
  ]
},
{
    path: '/addproducts',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'addproducts', 
        component: () => import('@/Admin/views/Addproducts.vue')
      }
    ]
  },
  {
    path: '/editproduct',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'editproduct', 
        component: () => import('@/Admin/views/Editproduct.vue')
      }
    ]
  },
  {
    path: '/orders',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'orders', 
        component: () => import('@/Admin/views/Orders.vue')
      }
    ]
  },
  {
    path: '/customers',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'customers', 
        component: () => import('@/Admin/views/ListCustomers.vue')
      }
    ]
  },
  {
    path: '/editcustomer',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'editcustomer', 
        component: () => import('@/Admin/views/Editcustomer.vue')
      }
    ]
  },
  {
    path: '/addcategory',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'addcategory', 
        component: () => import('@/Admin/views/Addcategory.vue')
      }
    ]
  },
  {
    path: '/allcategories',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'allcategories', 
        component: () => import('@/Admin/views/Allcategories.vue')
      }
    ]
  },
  {
    path: '/editcategory',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'editcategory', 
        component: () => import('@/Admin/views/Editcategory.vue')
      }
    ]
  },
  {
    path: '/admin/profile',
    component: AdminDashboard,
     meta: { requiresAdminAuth: true },
    children: [
      { 
        path: '', 
        name: 'profile', 
        component: () => import('@/Admin/views/Profile.vue')
      }
    ]
  },
  
  ]


const router = createRouter({
  history: createWebHistory(),
  routes
})
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAdminAuth) {
    const adminToken = localStorage.getItem('adminToken')
    if (!adminToken) {
      return next({ name: 'adminlogin' }) // redirect to admin login
    }
  }
  next()
})

export default router