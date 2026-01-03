<template>
  <div class="categories-container">
    <h2>Categories</h2>

    <div class="controls">
      <button @click="fetchCategories" :disabled="loading" class="btn">Refresh</button>
      <button @click="goToAdd" class="btn primary">Add Category</button>
      <span v-if="loading" class="loading">Loading...</span>
    </div>

    <p v-if="error" class="error">{{ error }}</p>

    <ul v-if="!loading && categories.length" class="categories-list">
      <li v-for="cat in categories" :key="cat.id" class="category-item">
        <div class="category-main">
          <strong class="category-name">{{ cat.name }}</strong>
          <span class="category-desc" v-if="cat.description">— {{ cat.description }}</span>
        </div>

        <div class="category-actions">
          
          <div class="action-buttons">
            <button @click="editCategory(cat.id)" class="btn small">Edit</button>
            <button
              @click="deleteCategory(cat.id)"
              class="btn small danger"
              :disabled="deletingId === cat.id"
            >
              <span v-if="deletingId === cat.id">Deleting...</span>
              <span v-else>Delete</span>
            </button>
          </div>
        </div>
      </li>
    </ul>

    <p v-if="!loading && !categories.length" class="empty">No categories found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import adminApi from '@/api/adminaxois'

const categories = ref([])
const loading = ref(false)
const error = ref('')
const deletingId = ref(null)

const router = useRouter()

async function fetchCategories() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminApi.get('/categories')
    // Accept both raw array and { data: [...] } shapes
    if (Array.isArray(res.data)) {
      categories.value = res.data
    } else if (Array.isArray(res.data?.data)) {
      categories.value = res.data.data
    } else if (res.data) {
      // if backend wrapped single result
      categories.value = Array.isArray(res.data) ? res.data : [res.data]
    } else {
      categories.value = []
    }
  } catch (err) {
    console.error('Fetch categories error', err)
    error.value =
      err.response?.data?.message ||
      err.response?.data?.error ||
      err.message ||
      'Failed to fetch categories'
  } finally {
    loading.value = false
  }
}

function goToAdd() {
  router.push({ name: 'addcategory' })
}

function editCategory(id) {
  // Use named route (router must define 'editcategory' with :id param)
  router.push({ name: 'editcategory', params: { id } })
}

async function deleteCategory(id) {
  const confirmed = window.confirm('Are you sure you want to delete this category? This action cannot be undone.')
  if (!confirmed) return

  deletingId.value = id
  try {
    await adminApi.delete(`/categories/${id}`)
    // remove from local list
    categories.value = categories.value.filter(c => c.id !== id)
  } catch (err) {
    console.error('Delete category error', err)
    window.alert(err.response?.data?.message || 'Failed to delete category')
  } finally {
    deletingId.value = null
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

<style scoped>
.categories-container {
  max-width: 900px;
  margin: 40px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

h2 {
  margin: 0 0 16px;
  font-size: 1.5rem;
  color: #111827;
}

.controls {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

.btn {
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  background: #fff;
  cursor: pointer;
  font-weight: 600;
}

.btn.primary {
  background: #2563eb;
  color: #fff;
  border-color: transparent;
}

.btn.small {
  padding: 6px 8px;
  font-size: 0.9rem;
}

.btn.danger {
  background: #fff;
  color: #dc2626;
  border-color: #fca5a5;
}

.loading {
  color: #6b7280;
  font-size: 0.9rem;
}

.error {
  color: #dc2626;
  margin-bottom: 12px;
}

.categories-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.category-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  border: 1px solid #e6e7ea;
  border-radius: 8px;
  margin-bottom: 10px;
}

.category-main {
  display: flex;
  align-items: center;
  gap: 8px;
}

.category-name {
  font-weight: 600;
  color: #0f172a;
}

.category-desc {
  color: #6b7280;
  font-weight: 400;
}

.category-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.category-meta {
  color: #6b7280;
  font-size: 0.825rem;
}

.empty {
  color: #6b7280;
  text-align: center;
  padding: 24px 0;
}
</style>