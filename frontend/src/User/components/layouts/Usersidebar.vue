<template>
  <aside :class="['sidebar', { collapsed }]">
    <div class="sidebar-top">
      <div class="brand" @click="goHome"></div>

      <button class="collapse-btn" @click="toggle">
        <svg viewBox="0 0 24 24" width="18" height="18">
          <path v-if="!collapsed" d="M8 6l8 6-8 6V6z" fill="currentColor"/>
          <path v-else d="M16 6l-8 6 8 6V6z" fill="currentColor"/>
        </svg>
      </button>
    </div>

    <nav class="sidebar-nav">
      <ul>
        <li
          v-for="(item, idx) in items"
          :key="item.key"
          :class="{ active: activeIndex === idx }"
          @click="handleClick(item, idx)"
        >
          <span class="item-icon" v-html="item.icon"></span>
          <span v-if="!collapsed">{{ item.label }}</span>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const collapsed = ref(false)
const activeIndex = ref(0)

const items = [
  { key: 'dashboard', label: 'Dashboard', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zM13 21h8V11h-8v10zm0-18v6h8V3h-8z" fill="currentColor"/></svg>' },
  { key: 'cart', label: 'Cart', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" fill="currentColor"/></svg>'},
  { key: 'favourite', label: 'Favourite', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg>' },
  { key: 'myorder', label: 'My Orders', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M13 12h7v1.5h-7zm0-2.5h7V11h-7zm0 5h7V16h-7zM21 4H3c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 15h-9V6h9v13z" fill="currentColor"/></svg>' },
  { key: 'orderdetails', label: 'Order Details', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 7h10v2H7zm0 4h10v2H7zm0 4h7v2H7z" fill="currentColor"/></svg>' },
  { key: 'logout', label: 'Logout', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" fill="currentColor"/></svg>' },
]

const sidebarWidth = computed(() => collapsed.value ? '72px' : '250px')

const updateSidebarWidth = () => {
  document.documentElement.style.setProperty('--sidebar-width', sidebarWidth.value)
}

onMounted(updateSidebarWidth)

const toggle = () => {
  collapsed.value = !collapsed.value
  updateSidebarWidth()
}

const goHome = () => {
  router.push('/userdashboard')
  activeIndex.value = 0
}

const handleClick = (item, idx) => {
  activeIndex.value = idx

  const routes = {
    dashboard: '/userdashboard',
    cart: '/cart',
    favourite: '/favourite',
    myorder: '/myorder',
    orderdetails: '/orderdetails',
  }

  if (item.key === 'logout') {
    router.push('/')
  } else {
    router.push(routes[item.key])
  }
}
</script>


<style scoped>
.sidebar {
  width: 100%;
  height: 100vh;
  background: #ffffff;
  padding: 1.25rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  border-right: 1px solid #e9ecef;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 200ms cubic-bezier(.2,.9,.3,1);
  position: sticky;
  top: 0;
}

.sidebar.collapsed {
  width: 72px;
  padding: 0.6rem 0.5rem;
}

.sidebar-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  color: #344767;
  min-width: 0;
}

.brand-text {
  overflow: hidden;
  transition: opacity 160ms, max-width 160ms;
}

.sidebar.collapsed .brand-text {
  opacity: 0;
  max-width: 0;
  pointer-events: none;
}

.collapse-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 8px;
  border: none;
  background: transparent;
  cursor: pointer;
  color: #344767;
  transition: background 150ms;
}

.collapse-btn:hover {
  background: rgba(0,0,0,0.04);
}

.sidebar-nav {
  margin-top: 1.25rem;
  flex: 1;
  overflow: auto;
  padding-right: 6px;
}

.nav-heading {
  color: #7b809a;
  font-size: 0.72rem;
  text-transform: uppercase;
  margin: 0 0 0.6rem 0;
  font-weight: 700;
  letter-spacing: 0.5px;
  white-space: nowrap;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-nav li {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.6rem 0.75rem;
  margin-bottom: 0.35rem;
  border-radius: 0.5rem;
  cursor: pointer;
  color: #344767;
  font-weight: 600;
  transition: background 160ms ease, color 160ms ease;
  min-height: 40px;
  white-space: nowrap;
  overflow: hidden;
}

.sidebar-nav li:hover {
  background: #f8f9fa;
  color: #007bff;
}

.sidebar-nav li.active {
  background: linear-gradient(90deg,#007bff,#335eea);
  color: #fff;
  box-shadow: 0 6px 18px rgba(51,94,234,0.12);
}

.item-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 6px;
  background: rgba(52,71,103,0.04);
  color: #344767;
  flex: 0 0 34px;
}

.item-label {
  flex: 1;
  font-size: 0.95rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.item-badge {
  background: #e9eefb;
  color: #334ea6;
  padding: 0.12rem 0.45rem;
  border-radius: 999px;
  font-size: 0.75rem;
  flex-shrink: 0;
}

.sidebar.collapsed .nav-heading,
.sidebar.collapsed .item-label,
.sidebar.collapsed .item-badge {
  display: none;
}

.sidebar.collapsed .item-icon {
  background: transparent;
  justify-content: center;
  width: 36px;
  height: 36px;
}

.sidebar-nav::-webkit-scrollbar {
  width: 8px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.06);
  border-radius: 8px;
}

:focus {
  outline: 2px solid rgba(102,126,234,0.25);
  outline-offset: 2px;
}

@media (max-width: 900px) {
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 80;
  }
}

@media (prefers-reduced-motion: reduce) {
  .sidebar { 
    transition: none; 
  }
}
</style>