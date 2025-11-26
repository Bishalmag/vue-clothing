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
          <span>StyleAdmin</span>
        </div>
      </div>

      <button class="collapse-btn" @click="toggleSidebar" :title="isCollapsed ? 'Expand' : 'Collapse'">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
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
            :class="{ active: activeKey === item.key }"
            @click="handleClick(item)"
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
    
    <div class="sidebar-footer">
      <button class="logout-btn" @click="logout">
        <div class="item-icon">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path :d="icons.logout" />
          </svg>
        </div>
        <span v-if="!isCollapsed">Logout</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const isCollapsed = ref(false)
const activeKey = ref('dashboard')

const icons = {
  dashboard: "M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",
  addproducts: "M12 2l10 6.5v7L12 22 2 15.5v-7L12 2z M12 22v-6.5",
  inventory: "M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z",
  orders: "M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18 M16 10a4 4 0 0 1-8 0",
  customers: "M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M16 3.13a4 4 0 0 1 0 7.75",
  settings: "M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.18-.08a2 2 0 0 0-2 2v.44a2 2 0 0 0 2 2h.18a2 2 0 0 1 1.73 1l.25.43a2 2 0 0 1 0 2l.08.18a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.18.08a2 2 0 0 0 2-2v-.44a2 2 0 0 0-2-2h-.18a2 2 0 0 1-1.73-1l-.25-.43a2 2 0 0 1 0-2l.08-.18a2 2 0 0 0-2-2z",
  chevronLeft: "M15 18l-6-6 6-6",
  chevronRight: "M9 18l6-6-6-6",
  logout: "M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M16 17l5-5-5-5 M21 12H9"
}

const menuItems = [
  { key: 'dashboard', label: 'Dashboard', icon: icons.dashboard },
  { key: 'addproducts', label: 'Add Products', icon: icons.addproducts },
  { key: 'inventory', label: 'Inventory', icon: icons.inventory },
  { key: 'orders', label: 'Orders', icon: icons.orders },
  { key: 'customers', label: 'Customers', icon: icons.customers },
  { key: 'settings', label: 'Settings', icon: icons.settings }
]

const routes = {
  dashboard: '/admindashboard',
  addproducts: '/addproducts',
  inventory: '/inventory',
  orders: '/orders',
  customers: '/customers',
  settings: '/settings'
}

const handleClick = (item) => {
  activeKey.value = item.key
  router.push(routes[item.key])
}

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value
}

const goHome = () => {
  router.push('/admindashboard')
}

const logout = () => {
  router.push('/login')
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
}

.sidebar.collapsed {
  width: 70px;
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
  width: 32px;
  height: 32px;
  border-radius: 6px;
  border: none;
  background: #f3f4f6;
  cursor: pointer;
  color: #374151;
  transition: all 0.2s ease;
}

.collapse-btn:hover {
  background: #e5e7eb;
}

.sidebar-nav {
  flex: 1;
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

.item-label {
  flex: 1;
  font-size: 0.9rem;
}

.sidebar-footer {
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  color: #374151;
  font-weight: 500;
  transition: all 0.2s ease;
  width: 100%;
  background: none;
  border: none;
}

.logout-btn:hover {
  background: #f3f4f6;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-heading,
.sidebar.collapsed .item-label {
  display: none;
}

.sidebar.collapsed .nav-list li,
.sidebar.collapsed .logout-btn {
  justify-content: center;
  padding: 0.75rem;
}

@media (max-width: 900px) {
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
  }
}
</style>