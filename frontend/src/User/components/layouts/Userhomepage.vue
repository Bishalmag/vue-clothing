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
                <div class="price">${{ formatPrice(product.price) }}</div>
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
            <p class="price">${{ formatPrice(quickViewProduct.price) }}</p>
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'
import { useToast } from '@/composables/useToast'
import { useCart } from '@/composables/useCart'
import { useFavorites } from '@/composables/useFavorites'

const BASE_URL = 'http://localhost:8000'
const fallbackImage = 'https://via.placeholder.com/400x400?text=No+Image'

const products = ref([])
const selectedCategory = ref('All')
const sortBy = ref('featured')
const page = ref(1)
const perPage = 6
const quickViewProduct = ref(null)

const { showToast } = useToast()
const { addToCart } = useCart()
const { toggleFavorite, isFavorite } = useFavorites()

const formatPrice = v => (Number(v) || 0).toFixed(2)
const resolvePicture = pic => !pic ? fallbackImage : /^https?:\/\//.test(pic) ? pic : `${BASE_URL}/storage/${pic}`

const fetchProducts = async () => {
  try {
    const res = await api.get('/products') 
    const payload = Array.isArray(res.data) ? res.data : (res.data.data ?? res.data) 
    products.value = payload.map(p => ({ 
      ...p, 
      price: Number(p.price) || 0, 
      image: resolvePicture(p.picture), 
      category: p.category?.name || p.category || 'Uncategorized', 
      color: p.color || '' 
    }))
  } catch {
    showToast?.('Failed to load products', 'error')
  }
}

onMounted(fetchProducts)

const categories = computed(() => {
  return [...new Set(products.value.map(p => p.category).filter(Boolean))]
})

const filteredProducts = computed(() =>
  selectedCategory.value === 'All'
    ? products.value
    : products.value.filter(p => p.category === selectedCategory.value)
)

const sorted = computed(() => {
  const arr = [...filteredProducts.value]
  if (sortBy.value === 'price-asc') return arr.sort((a,b) => a.price - b.price)
  if (sortBy.value === 'price-desc') return arr.sort((a,b) => b.price - a.price)
  if (sortBy.value === 'name') return arr.sort((a,b) => (a.name||'').localeCompare(b.name||''))
  return arr
})

const pages = computed(() => Math.max(1, Math.ceil(sorted.value.length / perPage)))
const paginated = computed(() => {
  if (page.value > pages.value) page.value = pages.value
  const start = (page.value-1)*perPage
  return sorted.value.slice(start, start+perPage)
})

const selectCategory = cat => { selectedCategory.value = cat; page.value = 1 }
const handleAddToCart = p => { addToCart({ ...p, price: Number(p.price) }); showToast?.(`"${p.name}" added to cart!`, 'success') }
const handleToggleFavorite = toggleFavorite
const openQuickView = p => quickViewProduct.value = p
const closeQuickView = () => quickViewProduct.value = null
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