<script setup>
import { ref, onMounted } from 'vue'
import Usersidebar from './layouts/Usersidebar.vue'
import Usernavbar from './layouts/Usernavbar.vue'
import Userfooter from './layouts/Userfooter.vue'
import { toasts, fetchUser } from '@/composables/useToast' 


const isAuthenticated = ref(true)

onMounted(() => {
  const token = localStorage.getItem('auth_token')
  isAuthenticated.value = !!token
  
  if (token) {
    fetchUser()
  }
 
})

</script>

<template>
  <div class="app-layout">
    <Usersidebar />
    <div class="main-content-area">
      <Usernavbar />
      
      <main class="main-content">
        <router-view />
      </main>

      <Userfooter />
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container">
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        :class="['toast', `toast-${toast.type}`]"
      >
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<style scoped>
:root {
  --sidebar-width: 200px;
  --sidebar-collapsed-width: 72px;
}

.app-layout {
  display: flex;
  min-height: 100vh;
  width: 100%;
  background: var(--content-bg, #f6f8fb);
}

.sidebar {
  width: var(--sidebar-width);
  min-width: var(--sidebar-width);
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
  background: #fff;
  border-right: 1px solid #eef2f6;
  transition: width 0.3s ease;
}

.sidebar.collapsed {
  width: var(--sidebar-collapsed-width);
  min-width: var(--sidebar-collapsed-width);
}

.main-content-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  margin-left: var(--sidebar-width);
  width: calc(100% - var(--sidebar-width));
  transition: margin-left 0.3s ease, width 0.3s ease;
}

.sidebar.collapsed ~ .main-content-area {
  margin-left: var(--sidebar-collapsed-width);
  width: calc(100% - var(--sidebar-collapsed-width));
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding-top: -5px;
  min-height: calc(100vh - 120px);
}

.content-container {
  flex: 1;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem var(--page-gutter, 2px);
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  margin-left: -70px; 
}

.homepage {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
}

/* ✅ Add missing toast styles */
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 10000;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.toast {
  padding: 12px 20px;
  border-radius: 8px;
  color: white;
  max-width: 300px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  animation: slideIn 0.3s ease;
}

.toast-success {
  background: #10b981;
}

.toast-error {
  background: #ef4444;
}

.toast-info {
  background: #3b82f6;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar.mobile-open {
    transform: translateX(0);
  }
  
  .main-content-area {
    margin-left: 0 !important;
    width: 100% !important;
  }
  
  .main-content {
    padding-top: 10px;
  }
  
  .content-container {
    padding: 0rem;
  }
}
</style>