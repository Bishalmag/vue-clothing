<script setup>

const currentYear = new Date().getFullYear()
</script>

<template>
  <footer class="app-footer">
    <div class="container">
      
      <div class="copyright">
        &copy; {{ currentYear }} <strong>StyleAdmin</strong>. All rights reserved.
      </div>

      <div class="footer-links">
        <a href="#">Privacy</a>
        <span class="dot">·</span>
        <a href="#">Terms</a>
        <span class="version">v1.0.0</span>
      </div>

    </div>
  </footer>
</template>

<style scoped>
.app-footer {
  width: 100%;
  background-color: #ffffff;
  border-top: 1px solid #e5e7eb; /* Light gray border */
  padding: 1rem 2rem;
  color: #6b7280; /* Muted text color */
  font-size: 0.875rem; /* 14px */
  margin-top: auto; /* Pushes footer to bottom if content is short */
}

.container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1400px; /* Aligns with modern wide dashboards */
  margin: 0 auto;
}

.footer-links {
  display: flex;
  align-items: center;
  gap: 1rem;
}

a {
  color: #6b7280;
  text-decoration: none;
  transition: color 0.2s;
}

a:hover {
  color: #111827; /* Darker on hover */
}

.dot {
  font-weight: bold;
}

.version {
  background: #f3f4f6;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
  color: #374151;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
  .container {
    flex-direction: column;
    gap: 0.75rem;
    text-align: center;
  }
}
</style>