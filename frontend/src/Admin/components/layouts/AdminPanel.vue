<template>
  <div class="list-container">
    <div class="list-header">
      <h2>Products</h2>
      <div class="actions">
        <button @click="fetchProducts" :disabled="loading" class="btn">Refresh</button>
        <button @click="goToAdd" class="btn primary">Add Product</button>
      </div>
    </div>

    <p v-if="error" class="error">{{ error }}</p>

    <table v-if="!loading && products.length" class="items-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Picture</th>
          <th>Name</th>
          <th>Variations</th>
          <th>Price</th>
          <th>Category</th>
          <th class="actions-col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.id">
          <td class="text-left">{{ p.id }}</td>
          <td class="text-left">
            <img v-if="p.picture" :src="resolvePicture(p.picture)" alt="pic" class="thumb" />
            <span v-else class="no-thumb">—</span>
          </td>
          <td class="text-left">{{ p.name }}</td>
          <td class="text-left">{{ formatVariations(p.variations) }}</td>
          <td class="text-left">{{ formatPrice(p.price) }}</td>
          <td class="text-left">{{ p.category?.name || '—' }}</td>
          <td class="actions-col text-left">
            <button @click="editProduct(p.id)" class="btn small">Edit</button>
            <button @click="deleteProduct(p.id)" class="btn small danger" :disabled="deletingId === p.id">
              <span v-if="deletingId === p.id">Deleting...</span>
              <span v-else>Delete</span>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="!loading && !products.length" class="empty">No products found.</p>
    <div v-if="loading" class="loading">Loading products…</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import adminApi from '@/api/adminaxois'

const products = ref([])
const loading = ref(false)
const error = ref('')
const deletingId = ref(null)
const router = useRouter()

function resolvePicture(pic) {
  if (!pic) return ''
  return pic.startsWith('http') ? pic : `http://127.0.0.1:8000/storage/${pic}`
}

async function fetchProducts() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminApi.get('/products')
    products.value = Array.isArray(res.data) ? res.data : (res.data?.data ?? [])
  } catch (err) {
    console.error(err)
    error.value = err.response?.data?.message || err.message || 'Failed to fetch products'
  } finally {
    loading.value = false
  }
}

function formatPrice(val) {
  if (val === null || val === undefined) return '—'
  return typeof val === 'number' ? `$${val.toFixed(2)}` : val
}

function formatVariations(vars) {
  if (!vars || !vars.length) return '—'
  return vars.map(v => `${v.color} (${v.size}:${v.stock})`).join(', ')
}

function goToAdd() {
  router.push({ name: 'addproducts' })
}

function editProduct(id) {
  router.push({ name: 'editproduct', params: { id } })
}

async function deleteProduct(id) {
  if (!window.confirm('Are you sure you want to delete this product?')) return
  deletingId.value = id
  try {
    await adminApi.delete(`/products/${id}`)
    products.value = products.value.filter(p => p.id !== id)
  } catch (err) {
    console.error(err)
    window.alert(err.response?.data?.message || 'Failed to delete product')
  } finally {
    deletingId.value = null
  }
}

onMounted(fetchProducts)
</script>

<style scoped>
/* Button height decreased by 40% */
.btn {
  padding: 4px 12px; /* Reduced from 8px 12px (40% height reduction) */
  border-radius: 6px;
  border: 1px solid #d1d5db;
  background: #fff;
  cursor: pointer;
  font-weight: 600;
  height: 28px; /* Added fixed height */
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem; /* Slightly smaller font */
}

.btn.primary {
  background: #2563eb;
  color: #fff;
  border-color: transparent;
}

.btn.small {
  padding: 3px 8px; /* Reduced from 6px 8px */
  font-size: 0.85rem;
  height: 24px; /* Smaller height for small buttons */
}

.btn.danger {
  background: #fff;
  color: #dc2626;
  border-color: #fca5a5;
}

/* List container */
.list-container {
  max-width: 1200px;
  margin: 32px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.04);
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

/* Table styling with left-aligned content */
.items-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 8px;
}

.items-table th,
.items-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #f3f4f6;
  color: #111827;
  text-align: left; /* Force left alignment for all cells */
}

.items-table th {
  border-bottom: 1px solid #e6e7ea;
  color: #374151;
  font-weight: 600;
  text-align: left; /* Ensure headers are left-aligned too */
}

/* Specific left alignment classes */
.text-left {
  text-align: left !important;
  padding-left: 12px !important;
}

/* Reduce left padding to shift content left */
.items-table td {
  padding-left: 8px; /* Reduced from 12px */
  padding-right: 8px; /* Reduced from 12px */
}

.items-table th {
  padding-left: 8px; /* Reduced from 12px */
  padding-right: 8px; /* Reduced from 12px */
}

.thumb {
  width: 56px;
  height: 56px;
  object-fit: cover;
  border-radius: 6px;
}

.no-thumb {
  color: #6b7280;
}

.actions-col {
  width: 180px; /* Reduced from 220px */
}

.loading,
.empty {
  text-align: center;
  color: #6b7280;
  padding: 12px 0;
}

.error {
  color: #dc2626;
  margin-bottom: 12px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .list-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  
  .actions {
    width: 100%;
    justify-content: flex-start;
  }
  
  .btn {
    padding: 3px 10px;
    font-size: 0.85rem;
  }
  
  .items-table {
    font-size: 0.9rem;
  }
  
  .items-table td,
  .items-table th {
    padding: 6px 4px;
  }
  
  .thumb {
    width: 40px;
    height: 40px;
  }
  
  .actions-col {
    width: 140px;
  }
}
</style>