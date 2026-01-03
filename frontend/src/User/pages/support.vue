<template>
  <div class="page-container">
    <div class="content-card">
      <h1 class="page-title">Help & Support</h1>
      <p class="subtitle">We are here to help you.</p>
      
      <div class="divider"></div>

      <div class="contact-grid">
        <div class="contact-item">
          <div class="icon-box">📧</div>
          <h4>Email Us</h4>
          <p>support@fashionstore.com</p>
          <small>Response within 24 hours</small>
        </div>

        <div class="contact-item">
          <div class="icon-box">📞</div>
          <h4>Call Us</h4>
          <p>+1 (800) 123-4567</p>
          <small>Mon-Fri, 9am - 6pm EST</small>
        </div>
      </div>

      <div class="divider"></div>

      <div class="faq-section">
        <h3>Quick Actions</h3>
        <ul>
          <li>
            <router-link to="/returns">How do I return an item?</router-link>
          </li>
          <li>
            <router-link to="/orders">Where is my order?</router-link>
          </li>
          <li>
            <router-link to="/settings">Change my password</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-container {
  padding: 2rem;
  max-width: 800px;
  margin: 0 auto;
}

.content-card {
  background: #fff;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.02);
  border: 1px solid #eef2f6;
}

.page-title {
  color: #344767;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.subtitle {
  color: #67748e;
  font-size: 0.95rem;
}

.divider {
  height: 1px;
  background: #eef2f6;
  margin: 2rem 0;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.contact-item {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
  text-align: center;
}

.icon-box {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.contact-item h4 {
  color: #344767;
  margin: 0.5rem 0;
}

.contact-item p {
  color: #495057;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.contact-item small {
  color: #9ca3af;
  font-size: 0.8rem;
}

.faq-section h3 {
  color: #344767;
  font-size: 1.1rem;
  margin-bottom: 1rem;
}

.faq-section ul {
  list-style: none;
  padding: 0;
}

.faq-section li {
  margin-bottom: 0.75rem;
}

.faq-section a {
  color: #007bff;
  text-decoration: none;
  font-weight: 500;
}

.faq-section a:hover {
  text-decoration: underline;
}

@media (max-width: 600px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
}
</style>