import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/Homepage.vue'
import Loginform from '@/pages/loginform.vue'
import Signupform from '@/pages/signupform.vue'
import About from '@/pages/about.vue'
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
    path: '/about', 
    name: 'about', 
    component: About
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
    path: '/myorder',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'myorder', 
        component: () => import('@/User/views/MyOrder.vue')
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
    path: '/orderhistory',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'orderhistory', 
        component: () => import('@/User/views/OrderHistory.vue')
      }
    ]
  },
  {
    path: '/orderdetails',
    component: UserDashboard,
    children: [
      { 
        path: '', 
        name: 'orderdetails', 
        component: () => import('@/User/views/OrderDetails.vue')
      }
    ]
  },
  // Admin Routes
 {
  path: '/admindashboard',
  component: AdminDashboard,
  children: [
    { 
      path: '', 
      name: 'admindashboard', 
      component: AdminPanel
    }
  ]
},
// {
//     path: '/myorder',
//     component: UserDashboard,
//     children: [
//       { 
//         path: '', 
//         name: 'myorder', 
//         component: () => import('@/User/views/MyOrder.vue')
//       }
//     ]
//   },
  ]


const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router