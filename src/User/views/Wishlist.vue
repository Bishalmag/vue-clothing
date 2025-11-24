<template>
  <div class="wishlist-page">
    <div class="page-header">
      <h1>Wishlist</h1>
      <p>{{ items.length }} items saved for later</p>
    </div>

    <div class="wishlist-content">
      <!-- Empty State -->
      <div v-if="items.length === 0" class="empty-wishlist">
        <div class="empty-icon">⭐</div>
        <h2>Your wishlist is empty</h2>
        <p>Save items you love for later</p>
        <router-link to="/" class="shop-btn">
          Explore Products
        </router-link>
      </div>

      <!-- Wishlist Items -->
      <div v-else class="wishlist-grid">
        <div v-for="item in items" :key="item.id" class="wishlist-item">
          <button 
            @click="removeItem(item.id)" 
            class="remove-btn"
            aria-label="Remove from wishlist"
          >
            ×
          </button>
          
          <div class="item-image">
            <img :src="item.image" :alt="item.name" />
          </div>

          <div class="item-info">
            <h3>{{ item.name }}</h3>
            <p class="category">{{ item.category }}</p>
            <p class="price">${{ item.price }}</p>
          </div>

          <button 
            @click="moveToCart(item)" 
            class="cart-btn"
          >
            Add to Cart
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Sample wishlist data
const items = ref([
  {
    id: 1,
    name: 'Premium Jacket',
    category: 'Outerwear',
    price: 199.99,
    image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=300'
  },
  {
    id: 2,
    name: 'Designer Watch',
    category: 'Accessories',
    price: 299.99,
    image: 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=300'
  },
  {
    id: 3,
    name: 'Leather Boots',
    category: 'Footwear',
    price: 159.99,
    image: 'https://images.unsplash.com/photo-1542280756-74b2f55e73ab?w=300'
  },
  {
    id: 4,
    name: 'Smart Backpack',
    category: 'Bags',
    price: 89.99,
    image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300'
  }
])

const removeItem = (itemId) => {
  items.value = items.value.filter(item => item.id !== itemId)
}

const moveToCart = (item) => {
  console.log('Moving to cart:', item)
  // Add your cart logic here
  alert(`Added ${item.name} to cart!`)
  removeItem(item.id)
}
</script>

<style scoped>
.wishlist-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 2rem;
  color: #344767;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #6c757d;
}

/* Empty State */
.empty-wishlist {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-wishlist h2 {
  color: #344767;
  margin-bottom: 0.5rem;
}

.empty-wishlist p {
  color: #6c757d;
  margin-bottom: 2rem;
}

.shop-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
}

.shop-btn:hover {
  background: #2980b9;
}

/* Wishlist Grid */
.wishlist-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
}

.wishlist-item {
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  padding: 1.5rem;
  position: relative;
  text-align: center;
}

.remove-btn {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  cursor: pointer;
  font-size: 1.2rem;
  font-weight: bold;
}

.remove-btn:hover {
  background: #c0392b;
}

.item-image {
  margin-bottom: 1rem;
}

.item-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}

.item-info h3 {
  color: #344767;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.category {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.price {
  color: #3498db;
  font-weight: 600;
  font-size: 1.2rem;
  margin-bottom: 1rem;
}

.cart-btn {
  width: 100%;
  background: #2ecc71;
  color: white;
  border: none;
  padding: 0.75rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.cart-btn:hover {
  background: #27ae60;
}

/* Responsive */
@media (max-width: 768px) {
  .wishlist-page {
    padding: 1rem;
  }
  
  .wishlist-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
  }
}
</style>