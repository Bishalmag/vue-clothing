<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const isCollapsed = ref(false)
const route = useRoute()

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value
}

// SVG Paths for icons (Standard SVG 24x24 viewbox)
const icons = {
  dashboard: "M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",
  products: "M12 2l10 6.5v7L12 22 2 15.5v-7L12 2z M12 22v-6.5",
  inventory: "M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z",
  users: "M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M16 3.13a4 4 0 0 1 0 7.75",
  orders: "M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z M3 6h18 M16 10a4 4 0 0 1-8 0",
  settings: "M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.18-.08a2 2 0 0 0-2 2v.44a2 2 0 0 0 2 2h.18a2 2 0 0 1 1.73 1l.25.43a2 2 0 0 1 0 2l-.08.18a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.18.08a2 2 0 0 0 2-2v-.44a2 2 0 0 0-2-2h-.18a2 2 0 0 1-1.73-1l-.25-.43a2 2 0 0 1 0-2l.08-.18a2 2 0 0 0-2-2z M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z",
  chevronLeft: "M15 18l-6-6 6-6",
  chevronRight: "M9 18l6-6-6-6",
  logout: "M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M16 17l5-5-5-5 M21 12H9"
}

const menuItems = [
  { 
    category: 'Overview', 
    items: [
      { name: 'Dashboard', path: '/admin/dashboard', icon: icons.dashboard },
    ]
  },
  { 
    category: 'Clothing & Stock', 
    items: [
      { name: 'Add Product', path: '/admin/products/add', icon: icons.products }, // Using 'products' icon
      { name: 'Inventory', path: '/admin/inventory', icon: icons.inventory },
      { name: 'Orders', path: '/admin/orders', icon: icons.orders },
    ]
  },
  { 
    category: 'People', 
    items: [
      { name: 'View Customers', path: '/admin/customers', icon: icons.users },
    ]
  },
  { 
    category: 'System', 
    items: [
      { name: 'Settings', path: '/admin/settings', icon: icons.settings },
    ]
  }
]

const isActive = (path) => route.path === path
</script>

<template>
  <aside class="sidebar" :class="{ 'collapsed': isCollapsed }">
    
    <div class="sidebar-header">
      <div class="brand" v-if="!isCollapsed">
        <span class="brand-icon">👕</span>
        <h3>StyleAdmin</h3>
      </div>
      <button class="toggle-btn" @click="toggleSidebar">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path :d="isCollapsed ? icons.chevronRight : icons.chevronLeft" />
        </svg>
      </button>
    </div>

    <nav class="sidebar-nav">
      <div v-for="(group, idx) in menuItems" :key="idx" class="menu-group">
        <p v-if="!isCollapsed" class="group-title">{{ group.category }}</p>
        <div v-else class="divider"></div>

        <ul>
          <li v-for="(item, index) in group.items" :key="index">
            <router-link :to="item.path" class="nav-link" :class="{ 'active': isActive(item.path) }">
              
              <div class="icon-box">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path :d="item.icon" />
                </svg>
              </div>

              <span v-if="!isCollapsed" class="link-text">{{ item.name }}</span>
              
              <span v-if="isCollapsed" class="tooltip">{{ item.name }}</span>
            </router-link>
          </li>
        </ul>
      </div>
    </nav>

    <div class="sidebar-footer">
      <button class="nav-link logout-btn">
        <div class="icon-box">
          <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path :d="icons.logout" />
          </svg>
        </div>
        <span v-if="!isCollapsed" class="link-text">Logout</span>
      </button>
    </div>

  </aside>
</template>

<style scoped>
/* --- VARIABLES --- */
:root {
  --bg-color: rgba(255, 255, 255, 0.08);
  --text-color: #e0e0e0;
  --active-bg: rgba(0, 255, 255, 0.12);
  --active-text: #00eaff;
  --accent: #00eaff;
  --width-open: 250px;
  --width-closed: 70px;
}

/* --- LAYOUT --- */
.sidebar {
  display: flex;
  flex-direction: column;
  height: 100vh;
  width: var(--width-open);
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(18px);
  color: var(--text-color);
  transition: width 0.3s ease;
  border-right: 1px solid rgba(255, 255, 255, 0.15);
  overflow-x: hidden;
}

.sidebar.collapsed {
  width: var(--width-closed);
}

/* --- HEADER --- */
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.4rem;
  height: 64px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.brand {
  display: flex;
  align-items: center;
  gap: 10px;
  white-space: nowrap;
}

.brand h3 {
  margin: 0;
  color: #00eaff;
  font-size: 1.1rem;
  font-weight: 600;
}

.toggle-btn {
  background: transparent;
  border: none;
  color: var(--text-color);
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: 0.2s;
}

.toggle-btn:hover {
  background: rgba(0, 255, 255, 0.12);
  color: #00eaff;
  box-shadow: 0px 0px 8px #00eaff4a;
}

/* --- NAVIGATION --- */
.sidebar-nav {
  flex: 1;
  padding: 1rem 0;
  overflow-y: auto;
}

.menu-group {
  margin-bottom: 1.5rem;
}

.group-title {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 0 1.5rem;
  margin-bottom: 0.5rem;
  color: #7b809a;
  white-space: nowrap;
  font-weight: 600;
}

.divider {
  height: 1px;
  background: rgba(255, 255, 255, 0.15);
  margin: 0.5rem 1rem;
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  text-decoration: none;
  color: var(--text-color);
  transition: all 0.25s;
  position: relative;
  cursor: pointer;
  border-radius: 10px;
  margin: 0 0.5rem;
}

.nav-link:hover {
  background: rgba(0, 255, 255, 0.12);
  color: #00eaff;
  box-shadow: 0px 0px 8px #00eaff4a;
}

.nav-link.active {
  background: rgba(0, 255, 255, 0.12);
  color: #00eaff;
  border-right: 3px solid #00eaff;
  box-shadow: 0px 0px 12px rgba(0, 255, 255, 0.25);
}

.icon-box {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 24px;
}

.link-text {
  margin-left: 12px;
  font-weight: 500;
  white-space: nowrap;
  font-size: 0.95rem;
}

/* --- TOOLTIP --- */
.tooltip {
  position: absolute;
  left: 100%;
  top: 50%;
  transform: translateY(-50%);
  background: #000;
  color: #fff;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 12px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: 0.2s;
  z-index: 50;
  margin-left: 10px;
  backdrop-filter: blur(10px);
  background: rgba(0, 0, 0, 0.8);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar.collapsed .nav-link:hover .tooltip {
  opacity: 1;
}

/* --- FOOTER --- */
.sidebar-footer {
  border-top: 1px solid rgba(255, 255, 255, 0.15);
  padding: 0.5rem 0;
  margin: 0 0.5rem;
}

.logout-btn {
  width: 100%;
  background: none;
  border: none;
  font-family: inherit;
  font-size: 1rem;
  border-radius: 10px;
}

.logout-btn:hover {
  background: rgba(255, 56, 96, 0.12);
  color: #ff3860;
  box-shadow: 0px 0px 8px #ff38604a;
}

/* Scrollbar Styling */
.sidebar-nav::-webkit-scrollbar {
  width: 6px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}
</style>