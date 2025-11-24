<template>
  <aside :class="['sidebar', { collapsed }]" role="navigation" aria-label="Main sidebar">
    <div class="sidebar-top">
      <div class="brand" @click="goHome" role="button" tabindex="0" aria-label="userdashboard">
        <div class="brand-text" v-if="!collapsed">
          <small>Dashboard</small>
        </div>
      </div>

      <button class="collapse-btn" @click="toggle" :aria-pressed="collapsed" :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
          <path v-if="!collapsed" d="M8 6l8 6-8 6V6z" fill="currentColor"/>
          <path v-else d="M16 6l-8 6 8 6V6z" fill="currentColor"/>
        </svg>
      </button>
    </div>

    <nav class="sidebar-nav" aria-label="Pages">
      <h3 class="nav-heading" v-if="!collapsed">PAGES</h3>

      <ul>
        <li
          v-for="(item, idx) in items"
          :key="item.key"
          :class="{ active: activeIndex === idx }"
          @click="handleClick(item, idx)"
          role="button"
          tabindex="0"
          @keyup.enter="handleClick(item, idx)"
        >
          <span class="item-icon" aria-hidden="true" v-html="item.icon"></span>
          <span class="item-label" v-if="!collapsed">{{ item.label }}</span>
          <span class="item-badge" v-if="item.badge && !collapsed">{{ item.badge }}</span>
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
const year = new Date().getFullYear()

const items = [
  { key: 'dashboard', label: 'Dashboard', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zM13 21h8V11h-8v10zm0-18v6h8V3h-8z" fill="currentColor"/></svg>' },
  { key: 'profile', label: 'Profile', icon: '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" fill="currentColor"/></svg>' },
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
  router.push('/')
  activeIndex.value = 0
}

const handleClick = (item, idx) => {
  activeIndex.value = idx
  const routes = {
    dashboard: '/userdashboard',
    profile: '/profile',
  }
  router.push(routes[item.key])
  emit('navigate', item.key)
}

const emit = defineEmits(['navigate'])
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

.sidebar-footer {
  padding-top: 0.75rem;
  border-top: 1px solid #f0f3f7;
  color: #9aa3b2;
  font-size: 0.82rem;
  white-space: nowrap;
}

.sidebar.collapsed .nav-heading,
.sidebar.collapsed .item-label,
.sidebar.collapsed .item-badge,
.sidebar.collapsed .sidebar-footer {
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