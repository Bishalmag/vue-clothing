<template>
  <nav class="navbar" :class="{ 'scrolled': isScrolled }">
    <div class="container">
      
      <router-link to="/" class="brand">
        FashionStore<span class="dot">.</span>
      </router-link>

      <div class="desktop-menu">
        <router-link to="/" class="nav-link">Home</router-link>
        <router-link to="/about" class="nav-link">About</router-link>
        <router-link to="/adminlogin" class="nav-link admin-link">Admin</router-link>
      </div>

      <div class="nav-actions">
        <router-link to="/login" class="icon-btn desktop-only" aria-label="Account">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </router-link>

        <router-link to="/adminlogin" class="icon-btn desktop-only admin-icon" aria-label="Admin Login">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 15l-8-4V6l8 4 8-4v5l-8 4z"/>
            <path d="M4 10v8l8 4 8-4v-8"/>
          </svg>
        </router-link>

        <button class="mobile-toggle" @click="toggleMenu" aria-label="Menu">
          <div class="hamburger" :class="{ 'open': isMenuOpen }">
            <span></span><span></span><span></span>
          </div>
        </button>
      </div>

    </div>

    <transition name="slide">
      <div v-if="isMenuOpen" class="mobile-menu">
        <div class="mobile-links">
          <router-link to="/" @click="toggleMenu">Home</router-link>
          <router-link to="/about" @click="toggleMenu">About</router-link>
          <div class="divider"></div>
          <router-link to="/login" @click="toggleMenu">Login</router-link>
          <router-link to="/adminlogin" @click="toggleMenu" class="admin-mobile-link">Admin Login</router-link>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isScrolled = ref(false);
const isMenuOpen = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50;
};

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  document.body.style.overflow = isMenuOpen.value ? 'hidden' : '';
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  padding: 1.5rem 0;
  transition: all 0.3s ease;
  background: transparent;
  color: #fff;
}

.navbar.scrolled {
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(10px);
  padding: 1rem 0;
  box-shadow: 0 2px 20px rgba(0,0,0,0.05);
  color: #1a1a1a;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  font-size: 1.5rem;
  font-weight: 800;
  text-decoration: none;
  color: inherit;
  letter-spacing: -0.5px;
}
.dot { color: #4f46e5; }

.desktop-menu { 
  display: flex; 
  gap: 2.5rem; 
}

.nav-link {
  text-decoration: none;
  color: inherit;
  font-weight: 500;
  font-size: 0.95rem;
  position: relative;
  opacity: 0.9;
  transition: opacity 0.3s;
}

.nav-link:hover { 
  opacity: 1; 
}

.nav-link::after {
  content: ''; 
  position: absolute; 
  bottom: -4px; 
  left: 0; 
  width: 0; 
  height: 2px;
  background: currentColor; 
  transition: width 0.3s;
}

.nav-link:hover::after { 
  width: 100%; 
}

/* Admin link styling */
.admin-link {
  font-weight: 600;
  color: #4f46e5;
}

.nav-actions { 
  display: flex; 
  align-items: center; 
  gap: 1.25rem; 
}

.icon-btn {
  background: none; 
  border: none; 
  cursor: pointer; 
  color: inherit;
  padding: 0; 
  display: flex; 
  align-items: center; 
  text-decoration: none;
}

.admin-icon {
  color: #4f46e5;
}

.mobile-toggle { 
  display: none; 
  background: none; 
  border: none; 
  cursor: pointer; 
  z-index: 1002; 
  padding: 0; 
}

.hamburger span {
  display: block; 
  width: 24px; 
  height: 2px; 
  background: currentColor;
  margin: 5px 0; 
  transition: 0.3s;
}

.hamburger.open span:nth-child(1) { 
  transform: rotate(45deg) translate(5px, 5px); 
}

.hamburger.open span:nth-child(2) { 
  opacity: 0; 
}

.hamburger.open span:nth-child(3) { 
  transform: rotate(-45deg) translate(5px, -5px); 
}

.mobile-menu {
  position: fixed; 
  inset: 0; 
  background: white; 
  z-index: 1001;
  display: flex; 
  flex-direction: column; 
  justify-content: center; 
  align-items: center;
}

.mobile-links { 
  display: flex; 
  flex-direction: column; 
  gap: 2rem; 
  text-align: center; 
}

.mobile-links a {
  font-size: 1.5rem; 
  color: #1a1a1a; 
  text-decoration: none; 
  font-weight: 600;
}

.admin-mobile-link {
  color: #4f46e5 !important;
  font-weight: 700;
}

.divider { 
  height: 1px; 
  background: #eee; 
  width: 100%; 
}

@media (max-width: 768px) {
  .desktop-menu, .desktop-only { 
    display: none; 
  }
  .mobile-toggle { 
    display: block; 
  }
  .navbar { 
    color: #1a1a1a; 
    background: white; 
  }
  .navbar.scrolled { 
    padding: 1rem 0; 
  }
}

.slide-enter-active, .slide-leave-active { 
  transition: transform 0.3s ease-in-out; 
}

.slide-enter-from, .slide-leave-to { 
  transform: translateX(100%); 
}
</style>