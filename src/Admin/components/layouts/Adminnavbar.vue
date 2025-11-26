<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const emit = defineEmits(['toggle-sidebar'])

const showProfileMenu = ref(false)
const unreadNotifications = ref(2) // Demo data

// Dynamic Title based on Route Name
const pageTitle = computed(() => {
  // If route has a meta title, use it, otherwise capitalize the path
  return route.meta.title || route.name || 'Overview'
})

const toggleSidebar = () => {
  emit('toggle-sidebar')
}

const toggleProfileMenu = () => {
  showProfileMenu.value = !showProfileMenu.value
}

const logout = () => {
  // Add actual logout logic here (clear tokens, etc)
  router.push('/')
}

// Close dropdown when clicking outside
const closeDropdown = (e) => {
  if (!e.target.closest('.user-menu-container')) {
    showProfileMenu.value = false
  }
}

onMounted(() => { window.addEventListener('click', closeDropdown) })
onUnmounted(() => { window.removeEventListener('click', closeDropdown) })
</script>

<template>
  <header class="navbar">
    
    <div class="navbar-left">
      <button @click="toggleSidebar" class="icon-btn toggle-btn">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
      <h2 class="page-title">{{ pageTitle }}</h2>
    </div>

    <div class="navbar-right">
      
      <div class="search-box">
        <span class="search-icon">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </span>
        <input type="text" placeholder="Search..." />
      </div>

      <button class="icon-btn" title="Notifications">
        <div class="badge" v-if="unreadNotifications"></div>
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
      </button>

      <div class="user-menu-container">
        <button class="user-btn" @click.stop="toggleProfileMenu">
          <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" alt="Admin" class="avatar" />
          <span class="username">Admin</span>
          <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" :class="{ 'rotate': showProfileMenu }">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </button>

        <div v-if="showProfileMenu" class="dropdown">
          <div class="dropdown-header">
            <p class="role">Administrator</p>
            <p class="email">admin@style.com</p>
          </div>
          <div class="divider"></div>
          <router-link to="/admin/profile" class="dropdown-item">Profile</router-link>
          <router-link to="/admin/settings" class="dropdown-item">Settings</router-link>
          <div class="divider"></div>
          <button @click="logout" class="dropdown-item danger">Logout</button>
        </div>
      </div>

    </div>
  </header>
</template>

<style scoped>
.navbar {
  background: #ffffff;
  height: 64px;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 40;
}

/* Left Section */
.navbar-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.toggle-btn {
  display: none; /* Hidden on desktop, shown on mobile via media query */
}

.page-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
  margin: 0;
  text-transform: capitalize;
}

/* Right Section */
.navbar-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

/* Search Box */
.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box input {
  padding: 0.5rem 1rem 0.5rem 2.25rem;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  font-size: 0.875rem;
  width: 200px;
  outline: none;
  transition: all 0.2s;
  background: #f9fafb;
}

.search-box input:focus {
  border-color: #6366f1; /* Indigo focus */
  background: #fff;
  width: 240px; /* Expands slightly on focus */
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  color: #9ca3af;
  display: flex;
}

/* Buttons */
.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  color: #6b7280;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.icon-btn:hover {
  background: #f3f4f6;
  color: #111827;
}

.badge {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 8px;
  height: 8px;
  background-color: #ef4444;
  border-radius: 50%;
  border: 1px solid #fff;
}

/* User Menu */
.user-menu-container {
  position: relative;
}

.user-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: background 0.2s;
}

.user-btn:hover {
  background: #f3f4f6;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}

.username {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.user-btn svg {
  color: #9ca3af;
  transition: transform 0.2s;
}

.user-btn svg.rotate {
  transform: rotate(180deg);
}

/* Dropdown */
.dropdown {
  position: absolute;
  top: 120%;
  right: 0;
  width: 200px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  padding: 0.5rem 0;
  animation: slideIn 0.1s ease-out;
}

@keyframes slideIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.dropdown-header {
  padding: 0.5rem 1rem;
}

.role {
  font-size: 0.875rem;
  font-weight: 600;
  color: #111827;
  margin: 0;
}

.email {
  font-size: 0.75rem;
  color: #6b7280;
  margin: 0;
}

.divider {
  height: 1px;
  background: #e5e7eb;
  margin: 0.5rem 0;
}

.dropdown-item {
  display: block;
  width: 100%;
  text-align: left;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  color: #374151;
  text-decoration: none;
  background: none;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}

.dropdown-item:hover {
  background: #f9fafb;
}

.dropdown-item.danger {
  color: #ef4444;
}

.dropdown-item.danger:hover {
  background: #fef2f2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .navbar {
    padding: 0 1rem;
  }
  
  .toggle-btn {
    display: flex;
  }

  .search-box, .username {
    display: none; /* Hide search and username on small screens */
  }
}
</style>