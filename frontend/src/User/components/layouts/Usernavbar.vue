<template>
  <header class="navbar">
    <div class="nav-center">
      <form class="search-form" @submit.prevent="$emit('search', searchQuery)">
        <input
          v-model="searchQuery"
          class="search-input"
          type="text"
          placeholder="Type here..."
        />
        <button type="submit" class="search-btn">🔍</button>
      </form>
    </div>

    <div class="nav-right">
      <button class="nav-icon">
        🔔
        <span v-if="notificationCount > 0" class="badge">
          {{ notificationCount }}
        </span>
      </button>

      <div class="profile-container">
        <div class="profile" @click="toggleProfileMenu">
          <div class="avatar">
            {{ avatarLetter }}
          </div>
        </div>

        <div v-if="showProfileMenu" class="dropdown">
          <div class="dropdown-header">
            <p class="email">
              {{ user?.email || 'Loading...' }}
            </p>
          </div>
          <div class="divider"></div>
          <button class="dropdown-item danger" @click="logout">
            Logout
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'
import { useLocalStorage } from '@/composables/useLocalStorage'

const router = useRouter()

const searchQuery = ref('')
const showProfileMenu = ref(false)
const notificationCount = ref(2)

const user = useLocalStorage('user', null)

const avatarLetter = computed(() => {
  if (!user.value?.name) return '?'
  return user.value.name.charAt(0).toUpperCase()
})

const fetchUser = async () => {
  if (user.value) return
  try {
    const res = await api.get('/user')
    user.value = res.data
  } catch (e) {}
}

const toggleProfileMenu = () => {
  showProfileMenu.value = !showProfileMenu.value
}

const closeDropdown = () => {
  showProfileMenu.value = false
}

const logout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user')
  closeDropdown()
  router.push('/login')
}

const handleClickOutside = (e) => {
  const el = document.querySelector('.profile-container')
  if (el && !el.contains(e.target)) {
    closeDropdown()
  }
}

onMounted(() => {
  fetchUser()
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
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
  height: 30px;
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
  border-radius: 50%;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
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
  border: 1px solid rgba(0, 0, 0, 0.05);
  min-width: 220px;
  z-index: 1000;
  overflow: hidden;
  transform-origin: top right;
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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

@media (max-width: 768px) {
  .navbar {
    padding: 0.75rem 1rem;
  }

  .nav-center {
    display: none;
  }
}
</style>
