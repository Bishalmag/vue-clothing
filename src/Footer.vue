<template>
  <footer class="site-footer">
    <div class="container">
      
      <div class="footer-grid">
        
        <div class="footer-col brand-col">
          <h3 class="footer-logo">YourStore<span class="dot">.</span></h3>
          <p class="brand-desc">redefining modern streetwear with sustainable materials and timeless silhouettes.</p>
          <div class="social-icons">
            <a href="#" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
            <a href="#" aria-label="Twitter"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></a>
            <a href="#" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
          </div>
        </div>

        <div class="footer-col">
          <h4>Shop</h4>
          <ul class="footer-links">
            <li><router-link to="/new">New Arrivals</router-link></li>
            <li><router-link to="/men">Men</router-link></li>
            <li><router-link to="/women">Women</router-link></li>
            <li><router-link to="/accessories">Accessories</router-link></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Company</h4>
          <ul class="footer-links">
            <li><router-link to="/about">Our Story</router-link></li>
            <li><router-link to="/sustainability">Sustainability</router-link></li>
            <li><router-link to="/contact">Contact Us</router-link></li>
            <li><router-link to="/faq">FAQ</router-link></li>
          </ul>
        </div>

        <div class="footer-col newsletter-col">
          <h4>Stay in the Loop</h4>
          <p>Subscribe for exclusive drops and 10% off your first order.</p>
          <form @submit.prevent="handleSubscribe" class="footer-form">
            <input type="email" placeholder="email@address.com" required />
            <button type="submit" aria-label="Subscribe">&rarr;</button>
          </form>
        </div>

      </div>

      <div class="footer-bottom">
        <div class="copyright">
          &copy; 2024 YourStore Inc. All rights reserved.
        </div>
        <div class="legal-links">
          <router-link to="/privacy">Privacy Policy</router-link>
          <router-link to="/terms">Terms of Service</router-link>
        </div>
      </div>

    </div>
  </footer>
</template>

<script setup>
const handleSubscribe = () => {
  // Add newsletter logic here
  alert("Welcome to the club!");
};
</script>

<style scoped>
/* --- Base Styles --- */
.site-footer {
  background-color: #0a0a0a; /* Almost black for depth */
  color: #ffffff;
  padding: 5rem 0 2rem;
  border-top: 1px solid #222;
  font-family: 'Inter', sans-serif;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

/* --- Grid Layout --- */
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1.5fr; /* Brand gets more space, Newsletter gets space */
  gap: 4rem;
  margin-bottom: 4rem;
}

/* --- Typography --- */
.footer-logo {
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  letter-spacing: -0.5px;
}
.dot { color: #4f46e5; }

.brand-desc {
  color: #a1a1aa;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
  max-width: 300px;
}

h4 {
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: #fff;
}

/* --- Links --- */
.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 0.75rem;
}

.footer-links a, .legal-links a {
  color: #a1a1aa;
  text-decoration: none;
  font-size: 0.95rem;
  transition: color 0.3s ease;
}

.footer-links a:hover, .legal-links a:hover {
  color: #fff;
}

/* --- Social Icons --- */
.social-icons { display: flex; gap: 1rem; }
.social-icons a { color: #fff; opacity: 0.7; transition: 0.3s; }
.social-icons a:hover { opacity: 1; transform: translateY(-2px); }

/* --- Newsletter Form --- */
.footer-form {
  position: relative;
  display: flex;
  border-bottom: 1px solid #333;
  padding-bottom: 0.5rem;
}

.footer-form input {
  background: transparent;
  border: none;
  width: 100%;
  color: white;
  padding: 0.5rem 0;
  outline: none;
}

.footer-form input::placeholder { color: #555; }

.footer-form button {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0 0.5rem;
  transition: transform 0.3s;
}

.footer-form button:hover { transform: translateX(5px); }

/* --- Bottom Section --- */
.footer-bottom {
  border-top: 1px solid #222;
  padding-top: 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #52525b;
  font-size: 0.875rem;
}

.legal-links { display: flex; gap: 2rem; }

/* --- Responsive --- */
@media (max-width: 1024px) {
  .footer-grid { grid-template-columns: 1fr 1fr; gap: 3rem; }
}

@media (max-width: 640px) {
  .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; }
  .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
  .brand-col { order: 4; margin-top: 1rem; } /* Puts logo at bottom on mobile */
}
</style>