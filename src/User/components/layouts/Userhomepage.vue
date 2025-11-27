<template>
  <div class="homepage">
    <main class="container">
      <section class="hero">
        <div class="hero-text">
          <h2>Discover Your Style</h2>
          <p>Curated collections, seasonal drops, and new arrivals — hand selected for you.</p>

          <div class="controls">
            <div class="chips">
              <button
                v-for="c in ['All', ...categories]"
                :key="c"
                :class="['chip', { active: selectedCategory === c }]"
                @click="selectCategory(c)"
              >
                {{ c }}
              </button>
            </div>

            <label class="sort">
              Sort:
              <select v-model="sortBy">
                <option value="featured">Featured</option>
                <option value="price-asc">Price: Low → High</option>
                <option value="price-desc">Price: High → Low</option>
                <option value="name">Name</option>
              </select>
            </label>
          </div>
        </div>

        <div class="hero-media"></div>
      </section>

      <section class="featured">
        <header class="section-header">
          <h3>Featured Products</h3>
          <p class="muted">{{ filteredProducts.length }} items</p>
        </header>

        <div class="grid">
          <article
            v-for="product in paginated"
            :key="product.id"
            class="product-card"
          >
            <div class="media" :style="{ backgroundImage: `url(${product.image})` }"></div>

            <div class="info">
              <div class="meta">
                <h4 class="name">{{ product.name }}</h4>
                <div class="price">${{ product.price.toFixed(2) }}</div>
              </div>

              <div class="submeta">{{ product.category }} • {{ product.color }}</div>

              <div class="actions">
                <button @click="handleToggleFavorite(product)" class="fav">
                  <span v-if="isFavorite(product.id)">♥</span><span v-else>♡</span>
                </button>

                <button @click="handleAddToCart(product)" class="add">Add</button>

                <button @click="openQuickView(product)" class="view">Quick view</button>
              </div>
            </div>
          </article>
        </div>

        <div class="pagination" v-if="pages > 1">
          <button @click="page = Math.max(1, page - 1)" :disabled="page === 1">Prev</button>
          <span>Page {{ page }} / {{ pages }}</span>
          <button @click="page = Math.min(pages, page + 1)" :disabled="page === pages">Next</button>
        </div>
      </section>
    </main>

    <div class="modal-backdrop" v-if="quickViewProduct" @click.self="closeQuickView">
      <div class="modal">
        <button class="modal-close" @click="closeQuickView">✕</button>
        <div class="modal-content">
          <div class="modal-media" :style="{ backgroundImage: `url(${quickViewProduct.image})` }"></div>
          <div class="modal-info">
            <h3>{{ quickViewProduct.name }}</h3>
            <p class="muted">{{ quickViewProduct.category }} • {{ quickViewProduct.color }}</p>
            <p class="price">${{ quickViewProduct.price.toFixed(2) }}</p>
            <p class="desc">{{ quickViewProduct.description }}</p>
            <div class="modal-actions">
              <button @click="handleAddToCart(quickViewProduct)" class="add">Add to cart</button>
              <button @click="handleToggleFavorite(quickViewProduct)" class="fav">
                <span v-if="isFavorite(quickViewProduct.id)">♥ Remove</span>
                <span v-else>♡ Favorite</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <aside class="cart-drawer" :class="{ open: cartOpen }">
      <header>
        <h4>Cart ({{ cartCount }})</h4>
        <button @click="cartOpen = false">✕</button>
      </header>
      <div class="cart-body">
        <p v-if="cartCount === 0" class="muted">Your cart is empty.</p>
        <ul>
          <li v-for="item in cart" :key="item.id">
            {{ item.name }} — ${{ item.price.toFixed(2) }} x {{ item.quantity }}
          </li>
        </ul>
      </div>
      <footer>
        <button class="checkout" :disabled="cartCount === 0" @click="checkout">Checkout</button>
      </footer>
    </aside>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useToast } from '@/composables/useToast'
import { useCart } from '@/composables/useCart'
import { useFavorites } from '@/composables/useFavorites'

const selectedCategory = ref('All')
const sortBy = ref('featured')
const page = ref(1)
const perPage = 6
const quickViewProduct = ref(null)
const cartOpen = ref(false)

const { showToast } = useToast()
const { addToCart, cart } = useCart()
const { toggleFavorite, isFavorite } = useFavorites()
const cartCount = computed(() => cart.value.length)

const categories = ['Tops', 'Bottoms', 'Outerwear', 'Accessories', 'Shoes']

const products = ref([
  { id: 1, name: 'Classic White Tee', category: 'Tops', color: 'White', price: 19.99, image: 'https://picsum.photos/400/400?random=1', description: 'A comfortable classic tee for everyday wear.' },
  { id: 2, name: 'Denim Jacket', category: 'Outerwear', color: 'Blue', price: 89.0, image: 'https://picsum.photos/400/400?random=2', description: 'Stylish denim jacket with a relaxed fit.' },
  { id: 3, name: 'Cargo Pants', category: 'Bottoms', color: 'Khaki', price: 49.5, image: 'https://picsum.photos/400/400?random=3', description: 'Utility-inspired cargo pants with lots of pockets.' },
  { id: 4, name: 'Leather Sneakers', category: 'Shoes', color: 'Black', price: 120.0, image: 'https://picsum.photos/400/400?random=4', description: 'Durable leather sneakers built for comfort.' },
  { id: 5, name: 'Chunky Scarf', category: 'Accessories', color: 'Grey', price: 29.0, image: 'https://picsum.photos/400/400?random=5', description: 'Warm scarf knitted from soft yarn.' },
  { id: 6, name: 'Striped Shirt', category: 'Tops', color: 'Navy', price: 35.25, image: 'https://picsum.photos/400/400?random=6', description: 'Casual striped shirt with a slim cut.' },
  { id: 7, name: 'Trench Coat', category: 'Outerwear', color: 'Beige', price: 159.0, image: 'https://picsum.photos/400/400?random=7', description: 'Classic trench for transitional seasons.' },
  { id: 8, name: 'Chino Shorts', category: 'Bottoms', color: 'Olive', price: 29.99, image: 'https://picsum.photos/400/400?random=8', description: 'Lightweight cotton shorts for warm days.' },
  { id: 9, name: 'Slip-On Loafers', category: 'Shoes', color: 'Tan', price: 79.0, image: 'https://picsum.photos/400/400?random=9', description: 'Smart casual loafers to elevate your outfit.' }
])

const filteredProducts = computed(() => {
  return products.value.filter(p => 
    selectedCategory.value === 'All' ? true : p.category === selectedCategory.value
  )
})

const sorted = computed(() => {
  const arr = [...filteredProducts.value]
  if (sortBy.value === 'price-asc') return arr.sort((a,b) => a.price - b.price)
  if (sortBy.value === 'price-desc') return arr.sort((a,b) => b.price - a.price)
  if (sortBy.value === 'name') return arr.sort((a,b) => a.name.localeCompare(b.name))
  return arr
})

const pages = computed(() => Math.max(1, Math.ceil(sorted.value.length / perPage)))
const paginated = computed(() => {
  if (page.value > pages.value) page.value = pages.value
  const start = (page.value - 1) * perPage
  return sorted.value.slice(start, start + perPage)
})

function selectCategory(cat) {
  selectedCategory.value = cat
  page.value = 1
}

function handleAddToCart(product) {
  addToCart(product)
  showToast(`"${product.name}" added to cart!`, 'success')
}

function handleToggleFavorite(product) {
  toggleFavorite(product)
}

function openQuickView(product) {
  quickViewProduct.value = product
}

function closeQuickView() {
  quickViewProduct.value = null
}

function checkout() {
  if (cartCount.value === 0) return
  showToast('Order placed successfully!', 'success')
  cartOpen.value = false
}
</script>

<style scoped>
.homepage {
  font-family: Inter, system-ui, sans-serif;
  background: #f6f8fb;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.hero {
  display: flex;
  gap: 2rem;
  background: linear-gradient(90deg,#fff,#fbfdff);
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 6px 20px rgba(16,24,40,0.04);
  margin-bottom: 2rem;
}

.hero-text {
  flex: 1;
}

.hero-text h2 {
  margin: 0 0 0.5rem 0;
  color: #1f2d3d;
  font-size: 1.8rem;
}

.hero-text p {
  margin: 0 0 1.5rem 0;
  color: #6b7280;
}

.controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.chips {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.chip {
  background: #f1f3f5;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 600;
  color: #4b5563;
}

.chip.active {
  background: #344767;
  color: white;
}

.sort select {
  padding: 0.5rem;
  border-radius: 6px;
  border: 1px solid #e6e9ef;
}

.hero-media {
  width: 300px;
  height: 100px;
  border-radius: 8px;
  background-image: url('https://source.unsplash.com/collection/888146/600x400');
  background-size: cover;
  background-position: center;
  box-shadow: 0 6px 20px rgba(16,24,40,0.06);
}

.featured {
  margin-top: 2rem;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 1.5rem;
}

.section-header h3 {
  margin: 0;
  color: #233044;
  font-size: 1.5rem;
}

.muted {
  color: #6b7280;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.product-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 6px 18px rgba(16,24,40,0.04);
}

.media {
  height: 200px;
  background-size: cover;
  background-position: center;
}

.info {
  padding: 1rem;
}

.meta {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 0.5rem;
}

.name {
  margin: 0;
  font-size: 1rem;
  color: #1f2d3d;
}

.price {
  font-weight: 700;
  color: #344767;
}

.submeta {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 1rem;
}

.actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.fav, .add, .view {
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.fav {
  background: transparent;
  border: 1px solid #e6e9ef;
}

.add {
  background: #007bff;
  color: white;
  border: none;
}

.view {
  background: transparent;
  border: none;
  color: #6b7280;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 1rem;
  align-items: center;
}

.pagination button {
  padding: 0.5rem 1rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: white;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 15, 25, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.modal {
  background: white;
  width: min(800px, 90%);
  border-radius: 10px;
  position: relative;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}

.modal-content {
  display: flex;
  gap: 2rem;
  padding: 2rem;
}

.modal-media {
  width: 300px;
  height: 300px;
  background-size: cover;
  background-position: center;
  border-radius: 8px;
}

.modal-info {
  flex: 1;
}

.modal-info h3 {
  margin: 0 0 0.5rem 0;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.cart-drawer {
  position: fixed;
  top: 0;
  right: -320px;
  width: 320px;
  height: 100%;
  background: white;
  box-shadow: -8px 0 28px rgba(16,24,40,0.12);
  transition: right 0.3s ease;
  display: flex;
  flex-direction: column;
  z-index: 1000;
}

.cart-drawer.open {
  right: 0;
}

.cart-drawer header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #eef2f6;
}

.cart-body {
  padding: 1rem;
  flex: 1;
  overflow: auto;
}

.cart-drawer footer {
  padding: 1rem;
  border-top: 1px solid #eef2f6;
}

.checkout {
  background: #1f8ef1;
  color: white;
  border: none;
  padding: 1rem;
  border-radius: 6px;
  font-weight: 700;
  width: 100%;
  cursor: pointer;
}

.checkout:disabled {
  background: #ccc;
  cursor: not-allowed;
}

@media (max-width: 980px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .hero {
    flex-direction: column;
  }
  
  .hero-media {
    width: 100%;
    height: 200px;
  }
}

@media (max-width: 768px) {
  .container {
    padding: 1rem;
  }
  
  .controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .modal-content {
    flex-direction: column;
  }
  
  .modal-media {
    width: 100%;
    height: 250px;
  }
}

@media (max-width: 560px) {
  .grid {
    grid-template-columns: 1fr;
  }
}
</style>