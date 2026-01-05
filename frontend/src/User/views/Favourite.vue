<script setup>
import { useFavorites } from '@/composables/useFavorites'
import { useCart } from '@/composables/useCart'
import { useToast } from '@/composables/useToast'

const { favorites, toggleFavorite, favoritesCount } = useFavorites()
const { addToCart } = useCart()
const { showToast } = useToast()

const removeFromFavorites = item => {
  toggleFavorite(item)
  showToast(`${item.name} removed from favorites`, 'info')
}

const handleAddToCart = item => {
  addToCart({ ...item, variation_id: item.variation_id ?? null })
  showToast(`Added ${item.name} to cart`, 'success')
}
</script>

<template>
  <div class="favorites-page">
    <div class="favorites-header">
      <h1>My Favorites</h1>
      <p>{{ favoritesCount ? `${favoritesCount} items in favorites` : 'No items in favorites' }}</p>
    </div>

    <div v-if="favoritesCount === 0" class="empty-favorites">
      <div class="empty-icon">❤️</div>
      <h2>No favorites yet</h2>
      <p>Start adding items you love</p>
      <router-link to="/" class="shop-now-btn">Shop Now</router-link>
    </div>

    <div v-else class="favorites-grid">
      <div v-for="item in favorites" :key="item.id" class="favorite-item">
        <div class="item-image">
          <img :src="item.image" :alt="item.name" />
        </div>

        <div class="item-info">
          <h3>{{ item.name }}</h3>
          <p class="category">{{ item.category }}</p>
          <p class="price">${{ item.price }}</p>
        </div>

        <div class="item-actions">
          <button class="add-to-cart-btn" @click="handleAddToCart(item)">
            Add to Cart
          </button>

          <button class="remove-btn" @click="removeFromFavorites(item)">
            Remove
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.favorites-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.favorites-header {
  text-align: center;
  margin-bottom: 2rem;
}

.empty-favorites {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.shop-now-btn {
  display: inline-block;
  background: #3498db;
  color: #fff;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
}

.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.favorite-item {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
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

.item-info {
  margin-bottom: 1rem;
}

.item-info h3 {
  margin-bottom: 0.25rem;
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
}

.item-actions {
  margin-top: auto;
  display: flex;
  gap: 0.75rem;
}

.add-to-cart-btn {
  flex: 1;
  background: #2ecc71;
  color: #fff;
  border: none;
  padding: 0.75rem;
  border-radius: 6px;
  cursor: pointer;
}

.remove-btn {
  flex: 1;
  background: #e74c3c;
  color: #fff;
  border: none;
  padding: 0.75rem;
  border-radius: 6px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .favorites-page {
    padding: 1rem;
  }

  .favorites-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}

@media (max-width: 480px) {
  .favorites-grid {
    grid-template-columns: 1fr;
  }
}
</style>
