<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="brand-section">
        <span class="brand-name">Clothing Store</span>
        <span class="dot">.</span>
      </div>
      <p class="copyright">&copy; 2025 Clothing Store</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer'
}
</script>

<style scoped>
.footer {
  background: white;
  padding: 1.5rem 0;
  border-top: 1px solid #f3f4f6;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
}

.brand-section {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.brand-name {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.dot {
  color: #4f46e5;
  font-weight: 700;
}

.copyright {
  color: #6b7280;
  font-size: 0.85rem;
  margin: 0;
}

@media (max-width: 768px) {
  .footer {
    padding: 1.25rem 0;
  }
  
  .footer-content {
    padding: 0 1.5rem;
  }
}
</style>