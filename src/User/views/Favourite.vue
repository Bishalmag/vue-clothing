<template>
  <div class="favourites-page">
    <div class="favourites-header">
      <h1>My Favourites</h1>
      <p v-if="favouriteItems.length > 0">{{ favouriteItems.length }} items in favourites</p>
      <p v-else>No items in favourites</p>
    </div>

    <div class="favourites-content">
      <!-- Empty State -->
      <div v-if="favouriteItems.length === 0" class="empty-favourites">
        <div class="empty-icon">❤️</div>
        <h2>No favourites yet</h2>
        <p>Start adding items you love</p>
        <router-link to="/" class="shop-now-btn">
          Shop Now
        </router-link>
      </div>

      <!-- Favourite Items Grid -->
      <div v-else class="favourites-grid">
        <div v-for="item in favouriteItems" :key="item.id" class="favourite-item">
          <div class="item-image">
            <img :src="item.image" :alt="item.name" />
            <button 
              @click="removeFromFavourites(item.id)" 
              class="remove-favourite-btn"
              aria-label="Remove from favourites"
            >
              ❤️
            </button>
          </div>
          
          <div class="item-info">
            <h3 class="item-name">{{ item.name }}</h3>
            <p class="item-category">{{ item.category }}</p>
            <p class="item-price">${{ item.price }}</p>
          </div>

          <div class="item-actions">
            <button 
              @click="addToCart(item)" 
              class="add-to-cart-btn"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Sample favourites data
const favouriteItems = ref([
  {
    id: 1,
    name: 'Classic White T-Shirt',
    category: 'T-Shirts',
    price: 29.99,
    image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300'
  },
  {
    id: 2,
    name: 'Slim Fit Jeans',
    category: 'Jeans',
    price: 79.99,
    image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=300'
  },
  {
    id: 3,
    name: 'Sports Sneakers',
    category: 'Shoes',
    price: 129.99,
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300'
  },
  {
    id: 4,
    name: 'Winter Jacket',
    category: 'Jackets',
    price: 149.99,
    image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=300'
  }
])

// Methods
const removeFromFavourites = (itemId) => {
  favouriteItems.value = favouriteItems.value.filter(item => item.id !== itemId)
}

const addToCart = (item) => {
  // Emit event or call store action to add to cart
  console.log('Added to cart:', item)
  // You can add toast notification here
  alert(`Added ${item.name} to cart!`)
}
</script>

<style scoped>
.favourites-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.favourites-header {
  text-align: center;
  margin-bottom: 2rem;
}

.favourites-header h1 {
  font-size: 2rem;
  color: #344767;
  margin-bottom: 0.5rem;
}

.favourites-header p {
  color: #6c757d;
  font-size: 1rem;
}

/* Empty State */
.empty-favourites {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-favourites h2 {
  color: #344767;
  margin-bottom: 0.5rem;
}

.empty-favourites p {
  color: #6c757d;
  margin-bottom: 2rem;
}

.shop-now-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.2s;
}

.shop-now-btn:hover {
  background: #2980b9;
}

/* Favourites Grid */
.favourites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.favourite-item {
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  padding: 1.5rem;
  transition: transform 0.2s, box-shadow 0.2s;
}

.favourite-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.item-image {
  position: relative;
  margin-bottom: 1rem;
}

.item-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}

.remove-favourite-btn {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  cursor: pointer;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.remove-favourite-btn:hover {
  transform: scale(1.1);
}

.item-info {
  margin-bottom: 1rem;
}

.item-name {
  color: #344767;
  font-size: 1.1rem;
  margin-bottom: 0.25rem;
  font-weight: 600;
}

.item-category {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.item-price {
  color: #3498db;
  font-weight: 600;
  font-size: 1.2rem;
}

.item-actions {
  display: flex;
  gap: 0.5rem;
}

.add-to-cart-btn {
  flex: 1;
  background: #2ecc71;
  color: white;
  border: none;
  padding: 0.75rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s;
}

.add-to-cart-btn:hover {
  background: #27ae60;
}

/* Responsive Design */
@media (max-width: 768px) {
  .favourites-page {
    padding: 1rem;
  }
  
  .favourites-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
  }
}

@media (max-width: 480px) {
  .favourites-grid {
    grid-template-columns: 1fr;
  }
}
</style>