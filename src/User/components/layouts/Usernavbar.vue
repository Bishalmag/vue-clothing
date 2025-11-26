<template>
  <header class="navbar">
    <div class="nav-center">
      <form class="search-form" @submit.prevent="$emit('search', searchQuery)">
        <input 
          v-model="searchQuery" 
          class="search-input" 
          type="text" 
          placeholder="Type here..."
        >
        <button type="submit" class="search-btn">
          <svg width="16" height="16" viewBox="0 0 16 16">
            <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>
      </form>
    </div>

    <div class="nav-right">
      
      <button class="nav-icon" aria-label="Notifications">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.93 16.36 5.36 13.5 4.68V4C13.5 3.17 12.83 2 12 2C11.17 2 10.5 3.17 10.5 4V4.68C7.63 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span v-if="notificationCount > 0" class="badge">{{ notificationCount }}</span>
      </button>

      <div class="profile-container">
        <div class="profile" @click="toggleProfileMenu" role="button" tabindex="0">
          <div class="avatar">A</div>
        </div>
        
        <div v-if="showProfileMenu" class="dropdown">
          <div class="dropdown-header">
            <p class="email">user@gmail.com</p>
          </div>
          <div class="divider"></div>
          <router-link to="/settings" class="dropdown-item" @click="closeDropdown">Settings</router-link>
          <div class="divider"></div>
          <button @click="logout" class="dropdown-item danger">Logout</button>
        </div>
      </div>

    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const searchQuery = ref('')
const showProfileMenu = ref(false)
const notificationCount = ref(2) // Mock data for notifications

const toggleProfileMenu = () => showProfileMenu.value = !showProfileMenu.value
const closeDropdown = () => showProfileMenu.value = false

const logout = () => {
  // Add actual logout logic here (clear tokens, etc.)
  router.push('/login')
  closeDropdown()
}

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  const profileContainer = document.querySelector('.profile-container')
  if (profileContainer && !profileContainer.contains(event.target)) {
    closeDropdown()
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  background: white;
  border-bottom: 1px solid #eef2f6;
  position: sticky;
  top: 0;
  z-index: 100;
  height: 30px; /* Fixed height for consistency */
}

.nav-left .breadcrumb {
  font-weight: 700;
  color: #344767;
  font-size: 1.1rem;
}

.nav-center {
  flex: 1;
  margin: 0 2rem;
  display: flex;
  justify-content: center;
}

.search-form {
  display: flex;
  align-items: center;
  width: 100%;
  max-width: 400px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background: #f8f9fa;
  transition: border-color 0.2s;
}

.search-form:focus-within {
  border-color: #667eea;
  background: #fff;
}

.search-input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.6rem 1rem;
  outline: none;
  color: #495057;
  font-size: 0.9rem;
}

.search-btn {
  background: transparent;
  border: none;
  padding: 0.6rem 1rem;
  color: #6c757d;
  cursor: pointer;
  display: flex;
  align-items: center;
}

.search-btn:hover {
  color: #344767;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.nav-icon {
  position: relative;
  background: transparent;
  border: none;
  padding: 0.5rem;
  border-radius: 8px;
  color: #6c757d;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-icon:hover {
  background: #f8f9fa;
  color: #344767;
}

.badge {
  position: absolute;
  top: 4px;
  right: 4px;
  background: #e74c3c;
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 0;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white; /* Adds a clean cut from the icon */
}

.profile-container {
  position: relative;
}

.profile {
  cursor: pointer;
  padding: 2px;
  border-radius: 50%;
  transition: box-shadow 0.2s;
}

.profile:hover {
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2152ff, #21d4fd);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.9rem;
}

.dropdown {
  position: absolute;
  top: 120%;
  right: 0;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0,0,0,0.05);
  min-width: 220px;
  z-index: 1000;
  overflow: hidden;
  transform-origin: top right;
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.dropdown-header {
  padding: 1rem;
  background: #f8f9fa;
}

.email {
  font-size: 0.85rem;
  color: #555;
  margin: 0;
  font-weight: 500;
  word-break: break-all;
}

.divider {
  height: 1px;
  background: #f0f0f0;
}

.dropdown-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  text-decoration: none;
  color: #344767;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background 0.2s;
}

.dropdown-item:hover {
  background: #f8f9fa;
}

.dropdown-item.danger {
  color: #e74c3c;
}

.dropdown-item.danger:hover {
  background: #fff5f5;
}

/* Responsive */
@media (max-width: 768px) {
  .navbar {
    padding: 0.75rem 1rem;
  }
  
  .nav-center {
    display: none; /* Hide search bar on mobile, or move to a separate row */
  }
}
</style>