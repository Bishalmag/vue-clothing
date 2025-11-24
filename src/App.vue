<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './Navbar.vue'
import Footer from './Footer.vue'

const route = useRoute()
const isAuthenticated = ref(false)

const isUserRoute = computed(() => {
  const userRoutes = [
    '/userdashboard',
    '/cart', 
    '/profile',
    '/myorder',
    '/orderhistory', 
    '/wishlist',
    '/favourite',
    '/orderdetails'
  ]
  return userRoutes.includes(route.path)
})

const isAdminRoute = computed(() => {
  const adminRoutes = [
    '/admindashboard'
    // '/admin/products'
  ]
  return adminRoutes.includes(route.path)
})

onMounted(() => {
  const user = localStorage.getItem('user')
  isAuthenticated.value = !!user
  
  if (!user) {
    localStorage.setItem('user', '{"name":"Test User"}')
    isAuthenticated.value = true
  }
})
</script>

<template>
  <div id="app">
    <!--  admin routes -->
    <template v-if="isAdminRoute">
      <router-view />
    </template>
    
    <!-- User dashboard -->
    <template v-else-if="isUserRoute">
      <router-view />
    </template>
    
    <!--  landing page -->
    <template v-else>
      <Navbar :is-authenticated="isAuthenticated" />
      <main class="main-content">
        <router-view />
      </main>
      <Footer />
    </template>
  </div>
</template>

<style scoped>
#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
}
</style>