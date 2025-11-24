<template>
  <header class="navbar">
    <div class="nav-left">
      <div class="breadcrumb">
        <span class="active">Dashboard</span>
      </div>
    </div>

    <div class="nav-center">
      <form class="search-form" @submit.prevent="$emit('search', searchQuery)">
        <input
          v-model="searchQuery"
          class="search-input"
          type="text"
          placeholder="Type here..."
        />
        <button type="submit" class="search-btn">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </button>
      </form>
    </div>

    <div class="nav-right">
      <router-link to="/cart" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M3 3H4.5L5.5 12.5H16.5L17.5 6H7" stroke="currentColor" stroke-width="1.5"/>
          <circle cx="7.5" cy="16.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
          <circle cx="15.5" cy="16.5" r="1.5" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        <span v-if="cartCount > 0" class="badge">{{ cartCount }}</span>
      </router-link>

      <router-link to="/favourite" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M10 17.5L8.55 16.15C4.4 12.5 2 10.225 2 7.5C2 5.25 3.75 3.5 6 3.5C7.35 3.5 8.675 4.125 9.5 5.1C10.325 4.125 11.65 3.5 13 3.5C15.25 3.5 17 5.25 17 7.5C17 10.225 14.6 12.5 10.45 16.15L10 17.5Z" 
                :fill="isFavorited ? '#e74c3c' : 'none'" 
                :stroke="isFavorited ? '#e74c3c' : 'currentColor'" 
                stroke-width="1.5"/>
        </svg>
      </router-link>

      <router-link to="/wishlist" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M15 2.5H5C4.44772 2.5 4 2.94772 4 3.5V17.5C4 17.7761 4.22386 18 4.5 18C4.63261 18 4.75979 17.9473 4.85355 17.8536L10 12.7071L15.1464 17.8536C15.2402 17.9473 15.3674 18 15.5 18C15.7761 18 16 17.7761 16 17.5V3.5C16 2.94772 15.5523 2.5 15 2.5Z" 
                stroke="currentColor" 
                stroke-width="1.5"/>
        </svg>
      </router-link>

      <router-link to="/myorder" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M16.5 6.66667H3.5M5.16667 3.33333H14.8333C15.7538 3.33333 16.5 4.07953 16.5 5V15C16.5 15.9205 15.7538 16.6667 14.8333 16.6667H5.16667C4.24619 16.6667 3.5 15.9205 3.5 15V5C3.5 4.07953 4.24619 3.33333 5.16667 3.33333Z" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </router-link>

      <router-link to="/orderhistory" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M10 5V10L13 13M10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10C17 13.866 13.866 17 10 17Z" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </router-link>
      <router-link to="/orderdetails" class="nav-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M12 6V12H16M12 20C8.13401 20 5 16.866 5 13C5 9.13401 8.13401 6 12 6C15.866 6 19 9.13401 19 13C19 16.866 15.866 20 12 20Z" stroke="currentColor" stroke-width="1.5"/>
        </svg>
      </router-link>

      <router-link to="/profile" class="profile">
        <div class="avatar">A</div>
      </router-link>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')
const cartCount = ref(3)
const isFavorited = ref(false)
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
  z-index: 30;
}

.nav-left {
  display: flex;
  align-items: center;
}

.breadcrumb {
  font-size: 0.875rem;
  font-weight: 600;
  color: #344767;
}

.nav-center {
  flex: 1;
  margin: 0 1rem;
  display: flex;
  justify-content: center;
}

.search-form {
  display: flex;
  width: 100%;
  max-width: 400px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
}

.search-form:focus-within {
  border-color: #3498db;
}

.search-input {
  flex: 1;
  border: none;
  padding: 0.75rem 1rem;
  outline: none;
  font-size: 0.9rem;
}

.search-input::placeholder {
  color: #adb5bd;
}

.search-btn {
  background: transparent;
  border: none;
  padding: 0.75rem;
  color: #6c757d;
  cursor: pointer;
}

.search-btn:hover {
  color: #3498db;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.nav-icon {
  position: relative;
  padding: 0.5rem;
  border-radius: 6px;
  color: #6c757d;
  text-decoration: none;
  transition: all 0.2s;
}

.nav-icon:hover {
  background: #f8f9fa;
  color: #3498db;
}

.badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background: #e74c3c;
  color: white;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.1rem 0.35rem;
  border-radius: 999px;
  min-width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile {
  padding: 0.25rem;
  border-radius: 8px;
  text-decoration: none;
  margin-left: 0.5rem;
}

.profile:hover {
  background: #f8f9fa;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea, #764ba2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .navbar {
    padding: 1rem;
  }
  
  .nav-center {
    margin: 0 0.5rem;
  }
  
  .nav-icon:nth-child(n+4) {
    display: none;
  }
}

@media (max-width: 640px) {
  .nav-center {
    display: none;
  }
  
  .nav-icon:nth-child(n+3) {
    display: none;
  }
}
</style>