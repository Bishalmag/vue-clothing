<template>
  <main class="landing-wrapper">
    <section class="hero">
      <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop" alt="Fashion Collection" class="hero-bg" />
      <div class="hero-overlay">
        <div class="hero-content">
          <span class="hero-tag">Summer Collection 2024</span>
          <h1 class="hero-title">Elevate Your<br>Everyday Look</h1>
          <router-link to="/shop" class="cta-button">Shop Collection</router-link>
        </div>
      </div>
    </section>

    <section class="section container">
      <div class="category-grid">
        <div v-for="cat in categories" :key="cat.id" class="category-card">
          <img :src="cat.image" :alt="cat.name" />
          <div class="category-content">
            <h3>{{ cat.name }}</h3>
            <router-link :to="cat.link" class="text-link">Explore &rarr;</router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="section bg-light">
      <div class="container">
        <div class="section-header">
          <h2>Trending Now</h2>
          <router-link to="/shop" class="view-all">View All</router-link>
        </div>
        <div class="product-grid">
          <div v-for="item in trendingItems" :key="item.id" class="product-card">
            <div class="product-image-wrapper">
              <img :src="item.image" :alt="item.name" />
              <button class="quick-add">+</button>
            </div>
            <div class="product-info">
              <h4>{{ item.name }}</h4>
              <p class="price">${{ item.price }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="newsletter section">
      <div class="newsletter-content">
        <h2>Join the Club</h2>
        <p>Get 10% off your first order and exclusive access to new drops.</p>
        <form @submit.prevent="subscribe" class="input-group">
          <input type="email" placeholder="Enter your email" required />
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue';

/* DATA: Mock data for categories and trending items to render the UI dynamically */
const categories = ref([
  { id: 1, name: 'Women', link: '/women', image: 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?w=600&q=80' },
  { id: 2, name: 'Men', link: '/men', image: 'https://images.unsplash.com/photo-1490578474871-521c12b436a5?w=600&q=80' },
  { id: 3, name: 'Accessories', link: '/accessories', image: 'https://images.unsplash.com/photo-1516762689617-e1cffcef479d?w=600&q=80' }
]);

const trendingItems = ref([
  { id: 1, name: 'Oversized Denim Jacket', price: 89.00, image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80' },
  { id: 2, name: 'Classic White Tee', price: 29.00, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80' },
  { id: 3, name: 'Urban Cargo Pants', price: 65.00, image: 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=600&q=80' },
  { id: 4, name: 'Minimalist Sneaker', price: 110.00, image: 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&q=80' }
]);

const subscribe = () => alert('Subscribed!');
</script>
<style scoped>
/* Variables & Reset */
.landing-wrapper { 
  color: #111; 
  font-family: 'Inter', sans-serif; 
}

.container { 
  max-width: 1200px; 
  margin: 0 auto; 
  padding: 0 1.5rem; 
}

.section { 
  padding: 5rem 0; 
}

.bg-light { 
  background: #f9f9f9; 
}

img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
  display: block; 
}

/* Hero */
.hero { 
  position: relative; 
  height: 85vh; 
  overflow: hidden; 
}

.hero-bg { 
  position: absolute; 
  inset: 0; 
  animation: scaleIn 10s ease-out; 
}

.hero-overlay { 
  position: absolute; 
  inset: 0; 
  background: rgba(0,0,0,0.3); 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  text-align: center; 
  color: white; 
}

.hero-tag { 
  text-transform: uppercase; 
  letter-spacing: 2px; 
  font-size: 0.875rem; 
  margin-bottom: 1rem; 
  display: block; 
}

.hero-title { 
  font-size: 4rem; 
  font-weight: 800; 
  line-height: 1.1; 
  margin-bottom: 2rem; 
}

.cta-button { 
  background: white; 
  color: black; 
  padding: 1rem 2.5rem; 
  text-decoration: none; 
  font-weight: 600; 
  transition: 0.3s; 
}

.cta-button:hover { 
  background: #111; 
  color: white; 
}

/* Categories */
.category-grid { 
  display: grid; 
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
  gap: 1.5rem; 
}

.category-card { 
  position: relative; 
  height: 400px; 
  overflow: hidden; 
}

.category-card img { 
  transition: transform 0.5s; 
}

.category-card:hover img { 
  transform: scale(1.05); 
}

.category-content { 
  position: absolute; 
  bottom: 0; 
  left: 0; 
  padding: 2rem; 
  color: white; 
  background: linear-gradient(transparent, rgba(0,0,0,0.7)); 
  width: 100%; 
}

.text-link { 
  color: white; 
  text-decoration: none; 
  border-bottom: 1px solid white; 
  padding-bottom: 2px; 
}

/* Trending */
.section-header { 
  display: flex; 
  justify-content: space-between; 
  align-items: flex-end; 
  margin-bottom: 2rem; 
}

.view-all { 
  color: #666; 
  text-decoration: none; 
  font-size: 0.9rem; 
}

.product-grid { 
  display: grid; 
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
  gap: 2rem; 
}

.product-card { 
  cursor: pointer; 
}

.product-image-wrapper { 
  position: relative; 
  aspect-ratio: 3/4; 
  margin-bottom: 1rem; 
  background: #eee; 
}

.quick-add { 
  position: absolute; 
  bottom: 1rem; 
  right: 1rem; 
  width: 35px; 
  height: 35px; 
  border-radius: 50%; 
  border: none; 
  background: white; 
  cursor: pointer; 
  opacity: 0; 
  transform: translateY(10px); 
  transition: 0.3s; 
  font-size: 1.2rem; 
}

.product-card:hover .quick-add { 
  opacity: 1; 
  transform: translateY(0); 
}

.product-info h4 { 
  font-size: 1rem; 
  font-weight: 500; 
  margin: 0; 
}

.price { 
  color: #666; 
  margin-top: 0.25rem; 
  font-size: 0.95rem; 
}

/* Newsletter */
.newsletter-content { 
  max-width: 500px; 
  margin: 0 auto; 
  text-align: center; 
}

.input-group { 
  display: flex; 
  gap: 0.5rem; 
  margin-top: 2rem; 
}

.input-group input { 
  flex: 1; 
  padding: 1rem; 
  border: 1px solid #ddd; 
  outline: none; 
}

.input-group button { 
  padding: 1rem 2rem; 
  background: #111; 
  color: white; 
  border: none; 
  cursor: pointer; 
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title { 
    font-size: 2.5rem; 
  }
  
  .category-card { 
    height: 250px; 
  }
  
  .input-group { 
    flex-direction: column; 
  }
}

@keyframes scaleIn { 
  from { 
    transform: scale(1.1); 
  } 
  to { 
    transform: scale(1); 
  } 
}
</style>