<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-left">
        <span class="copyright">© {{ year }}, made with</span>
        <span>for a better web.</span>
      </div>

      <nav class="footer-right">
        <a href="#" @click.prevent>About</a>
        <a href="#" @click.prevent>Support</a>
        <a href="#" @click.prevent>Privacy</a>
      </nav>
    </div>
  </footer>
</template>

<script>
export default {
  name: "AppFooter",
  data() {
    return {
      year: new Date().getFullYear()
    }
  }
}
</script>

<style scoped>
.footer {
  background: #fff;
  border-top: 1px solid #e9ecef;
  padding: 20px 0;
  width: 100%;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-left {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #6c757d;
  font-size: 14px;
}

.heart {
  color: #e74c3c;
}

.footer-right {
  display: flex;
  gap: 20px;
}

.footer-right a {
  color: #6c757d;
  text-decoration: none;
  font-size: 14px;
}

.footer-right a:hover {
  color: #3498db;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: 15px;
  }
  
  .footer-left {
    text-align: center;
  }
}
</style>