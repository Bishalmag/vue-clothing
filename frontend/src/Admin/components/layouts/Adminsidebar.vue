<template>
  <aside :class="['sidebar', { collapsed: isCollapsed }]">
    <div class="sidebar-top">
      <div class="brand" @click="goHome">
        <div class="brand-logo">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
          </svg>
        </div>
        <div class="brand-text" v-if="!isCollapsed">
          <span>Admin</span>
        </div>
      </div>
      <button class="collapse-btn" @click="toggleSidebar" :title="isCollapsed ? 'Expand' : 'Collapse'">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
          <path :d="isCollapsed ? icons.chevronRight : icons.chevronLeft" />
        </svg>
      </button>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section">
        <h3 class="nav-heading" v-if="!isCollapsed">PAGES</h3>
        <ul class="nav-list">
          <li
            v-for="item in menuItems"
            :key="item.key"
            :class="{ active: activeKey === item.key, 'logout-item': item.key === 'logout' }"
            @click="item.key === 'logout' ? logout() : handleClick(item)"
          >
            <span class="item-icon">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                <path :d="item.icon" />
              </svg>
            </span>
            <span class="item-label" v-if="!isCollapsed">{{ item.label }}</span>
          </li>
        </ul>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import adminApi from '@/api/adminaxois'

const router = useRouter()
const route = useRoute()
const isCollapsed = ref(false)
const activeKey = ref('dashboard')

const icons = {
  dashboard: "M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",
  addproducts: "M12 2l10 6.5v7L12 22 2 15.5v-7L12 2z M12 22v-6.5",
  allcategories: "M3 5a2 2 0 0 1 2-2h3v4H3V5zm0 6h3v4H3v-4zm6 0h3v4H9v-4zm6 0h3v4h-3v-4zm-6-6h3v4H9V5zm6 0h3v4h-3V5zm6 0h3a2 2 0 0 1 2 2v3h-5V5zm0 6h5v4h-5v-4zm-12 6h3v4H3v-4zm6 0h3v4H9v-4zm6 0h3v4h-3v-4zm6 0h5v3a2 2 0 0 1-2 2h-3v-5z",
  addcategory: "M20 6h-8l-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-5 10h-2v2h-2v-2H9v-2h2v-2h2v2h2v2z",
  orders: "M3 3h18v2H3V3zm0 6h18v2H3V9zm0 6h18v2H3v-2zm0 6h18v2H3v-2z",
  customers: "M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z",
  chevronLeft: "M15 18l-6-6 6-6",
  chevronRight: "M9 18l6-6-6-6",
  logout: "M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M16 17l5-5-5-5 M21 12H9"
}

const menuItems = [
  { key: 'dashboard', label: 'Dashboard', icon: icons.dashboard },
  { key: 'addproducts', label: 'Add Products', icon: icons.addproducts },
  { key: 'allcategories', label: 'All Categories', icon: icons.allcategories },
  { key: 'addcategory', label: 'Add Category', icon: icons.addcategory },
  { key: 'orders', label: 'Orders', icon: icons.orders },
  { key: 'customers', label: 'Customers', icon: icons.customers },
  { key: 'logout', label: 'Logout', icon: icons.logout } 
]

const routes = {
  dashboard: '/admindashboard',
  addproducts: '/addproducts',
  allcategories: '/allcategories',
  addcategory: '/addcategory',
  orders: '/orders',
  customers: '/customers',
}

const handleClick = (item) => {
  activeKey.value = item.key
  router.push(routes[item.key] ?? '/allproducts')
}

const toggleSidebar = () => isCollapsed.value = !isCollapsed.value
const goHome = () => router.push('/allproducts')

const logout = async () => {
  try {
    await adminApi.post('/admin/logout')
  } catch (err) {
    console.error('Error logging out:', err)
  } finally {
    // Clear ALL admin-related storage
    localStorage.removeItem('admin_token')
    localStorage.removeItem('adminToken')
    localStorage.removeItem('adminUser')
    
    // Redirect to login page
    router.push('/adminlogin').then(() => {
      window.location.reload()
    })
  }
}

watch(() => route.path, (path) => {
  const routeKey = Object.keys(routes).find(key => routes[key] === path)
  if (routeKey) activeKey.value = routeKey
}, { immediate: true })
</script>

<style scoped>
.sidebar {
  width: 250px;
  height: 100vh;
  background: white;
  padding: 1.5rem 1rem;
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
  position: sticky;
  top: 0;
  border-right: 1px solid #e5e7eb;
  z-index: 100;
}

.sidebar.collapsed {
  width: 100px;
  padding: 1rem 0.5rem;
}

.sidebar-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 2rem;
  padding: 0 0.5rem;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  color: #374151;
}

.brand-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background: #3b82f6;
  border-radius: 8px;
  color: white;
}

.brand-text span {
  font-weight: 700;
  font-size: 1.25rem;
  color: #111827;
}

.collapse-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: none;
  background: #f3f4f6;
  cursor: pointer;
  color: #374151;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.collapse-btn:hover {
  background: #e5e7eb;
  transform: scale(1.05);
}

.sidebar-nav {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.nav-heading {
  color: #6b7280;
  font-size: 0.75rem;
  text-transform: uppercase;
  margin: 0 0 1rem 0.75rem;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-list li {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  margin-bottom: 0.25rem;
  border-radius: 8px;
  cursor: pointer;
  color: #374151;
  font-weight: 500;
  transition: all 0.2s ease;
}

.nav-list li:hover {
  background: #f3f4f6;
}

.nav-list li.active {
  background: #3b82f6;
  color: white;
}

/* Logout item specific styles */
.nav-list li.logout-item {
  margin-top: auto;
  color: #ef4444;
}

.nav-list li.logout-item:hover {
  background: #fef2f2;
  color: #dc2626;
}

.nav-list li.logout-item .item-icon {
  background: #fee2e2;
}

.item-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: #f3f4f6;
  flex: 0 0 36px;
}

.nav-list li.active .item-icon {
  background: rgba(255, 255, 255, 0.2);
}

.nav-list li.active .item-icon svg {
  fill: white;
}

.item-label {
  flex: 1;
  font-size: 0.9rem;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-heading,
.sidebar.collapsed .item-label {
  display: none;
}

.sidebar.collapsed .nav-list li {
  justify-content: center;
  padding: 0.75rem;
}

.sidebar.collapsed .collapse-btn {
  width: 40px;
  height: 40px;
  background: #3b82f6;
  color: white;
}

.sidebar.collapsed .collapse-btn:hover {
  background: #2563eb;
}

@media (max-width: 900px) {
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  }
  
  .sidebar.collapsed .collapse-btn {
    position: absolute;
    top: 2rem;
    right: -10px;
    background: #3b82f6;
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }
}
</style>