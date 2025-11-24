<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Adminnavbar from './layouts/Adminnavbar.vue'
import Adminsidebar from './layouts/Adminsidebar.vue'
import Adminfooter from './layouts/Adminfooter.vue'

const isSidebarOpen = ref(false)

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const handleClickOutside = (event) => {
  if (window.innerWidth <= 768 && 
      !event.target.closest('.admin-sidebar') && 
      !event.target.closest('.sidebar-toggle')) {
    isSidebarOpen.value = false
  }
}

const handleResize = () => {
  if (window.innerWidth > 768) {
    isSidebarOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <div class="admin-dashboard">
    <Adminnavbar @toggle-sidebar="toggleSidebar" />
    
    <div class="admin-layout">
      <Adminsidebar :class="{ 'mobile-open': isSidebarOpen }" />
      
      <div class="main-content">
        <div class="content-wrapper">
          <router-view /> 
        </div>
      </div>
    </div>
    
    <Adminfooter /> 
    
  </div>
</template>

<style scoped>

.admin-dashboard {
  min-height: 100vh;
  display: flex;
  flex-direction: column; 
}

.admin-layout {
  display: flex;
  flex: 1; 
}

.main-content {
  flex: 1; 
  display: flex;
  flex-direction: column;
}

/* CRITICAL: The content-wrapper must allow the main content to scroll */
.content-wrapper {
  flex: 1;
  overflow-y: auto; 
  background-color: #f8fafc; 
  padding: 30px; 
}

/* --- MOBILE FIXES --- */
@media (max-width: 768px) {
  .admin-layout {
    margin-top: 60px; 
  }

  .admin-sidebar {
    width: 250px; 
    position: fixed;
    top: 60px;
    left: 0;
    height: calc(100vh - 60px);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 45;
  }

  .admin-sidebar.mobile-open {
    transform: translateX(0);
  }
  
  /* REMOVED: .admin-footer CSS block */
  
  .main-content {
    margin-left: 0; 
  }
}
</style>