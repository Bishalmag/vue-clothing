<template>
  <footer class="refresh-footer">
    <div class="container">
      
      <div class="footer-main-grid">
        
        <div class="brand-col">
          <h3 class="brand-logo">Clothing Store<span class="dot">.</span></h3>
          <p class="brand-mission">
            Redefining modern style with sustainable materials and thoughtful design.
          </p>
        </div>
      </div>

      <div class="footer-bottom-bar">
        <span class="copyright">
          &copy; 2025 Clothing Store Inc. All rights reserved.
        </span>
      </div>

    </div>
  </footer>
</template>

<script>
export default {
    name: 'RefreshedLandingFooter'
}
</script>

<style scoped>
.refresh-footer {
  background-color: #0d0d0d;
  color: #f7f7f7;
  padding: 4rem 0 1.5rem;
  border-top: 1px solid #1a1a1a;
}

.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

.footer-main-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 4rem;
  padding-bottom: 3rem;
  border-bottom: 1px solid #1a1a1a;
}

.brand-logo {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 0.75rem;
  letter-spacing: -1px;
  margin-left: 58%;
}
.dot { 
  color: #4f46e5; 
}

.brand-mission {
  color: #a1a1aa;
  line-height: 1.7;
  font-size: 1rem;
  max-width: 400px;
  margin-left: 58%;
}

.footer-bottom-bar {
  padding-top: 1.5rem;
  text-align: center;
}

.copyright {
  color: #52525b;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
}

@media (max-width: 768px) {
  .footer-main-grid {
    grid-template-columns: 1fr;
    gap: 2.5rem;
  }

  .brand-col {
    text-align: center; 
    margin-left: auto; 
    margin-right: auto;
  }
  
  .brand-mission {
    max-width: 100%; 
  }
}
</style>