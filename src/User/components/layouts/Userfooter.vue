<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-left">
        <span class="copyright">
          &copy; {{ year }} <strong>FashionStore</strong>. Made with 
          <span class="heart" aria-hidden="true">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </span> 
          for a better style.
        </span>
      </div>

      <div class="footer-right">
        <router-link to="/about" class="link">About Us</router-link>
        <router-link to="/privacy" class="link">Privacy Policy</router-link>
        <router-link to="/support" class="link">Help Center</router-link>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue';

const year = ref(new Date().getFullYear());
</script>

<style scoped>
.footer {
  background: #fff;
  border-top: 1px solid #eef2f6;
  padding: 1.25rem 0;
  width: 100%;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.25rem;
  flex-wrap: wrap;
}

.footer-left {
  display: flex;
  align-items: center;
  color: #67748e;
  font-size: 0.875rem;
}

.copyright strong {
  color: #344767;
  font-weight: 600;
}

.heart {
  color: #e74c3c;
  margin: 0 4px;
  display: inline-flex;
  vertical-align: middle;
}

.footer-right {
  display: flex;
  gap: 2rem;
}

.footer-right a {
  color: #67748e;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  transition: color 0.2s ease;
}

.footer-right a:hover {
  color: #344767;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: 0.75rem;
    text-align: center;
  }
  
  .footer-left {
    text-align: center;
    justify-content: center;
  }
  
  .footer-right {
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>