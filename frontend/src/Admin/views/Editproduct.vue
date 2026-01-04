<template>
  <div class="form-container">
    <h2>Edit Product</h2>

    <div v-if="loading && !loaded" class="loading">Loading product…</div>

    <form v-else @submit.prevent="submit" class="product-form" enctype="multipart/form-data">

      <label>Name *</label>
      <input v-model="form.name" type="text" required placeholder="Product name" />

      <label>Price *</label>
      <input v-model.number="form.price" type="number" step="0.01" required placeholder="0.00" />

      <label>Category *</label>
      <select v-model="form.category_id" required>
        <option value="">-- Select Category --</option>
        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
      </select>

      <label>Picture</label>
      <input type="file" accept="image/*" @change="onFileChange" />
      <div v-if="previewUrl || form.picture" class="preview-wrap">
        <img :src="previewUrl || resolvePicture(form.picture)" class="preview" />
        <button type="button" class="btn small" @click="clearFile">Remove</button>
      </div>

      <label>Description</label>
      <textarea v-model="form.description" placeholder="Description (optional)"></textarea>

      <div class="variations-section">
        <h3>Variations</h3>
        <div v-for="(v, index) in form.variations" :key="index" class="variation-row">
          <input v-model="v.color" placeholder="Color" required />
          <select v-model="v.size" required>
            <option value="">-- Select Size --</option>
            <option v-for="size in sizes" :key="size" :value="size">{{ size }}</option>
          </select>
          <input v-model.number="v.stock" type="number" placeholder="Stock" required />
          <button type="button" class="btn small danger" @click="removeVariation(index)">Remove</button>
        </div>
        <button type="button" class="btn small" @click="addVariation">Add Variation</button>
      </div>

      <div class="buttons">
        <button type="submit" class="btn primary" :disabled="saving">{{ saving ? 'Saving...' : 'Save Changes' }}</button>
        <button type="button" class="btn" @click="cancel" :disabled="saving">Cancel</button>
        <button type="button" class="btn danger" @click="confirmDelete" :disabled="deleting">{{ deleting ? 'Deleting...' : 'Delete' }}</button>
      </div>

      <p v-if="success" class="success">Product updated successfully.</p>
      <p v-if="error" class="error">{{ error }}</p>

    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import adminApi from '@/api/adminaxois'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const sizes = ['S', 'M', 'L', 'XL']

const form = reactive({
  name: '',
  price: 0,
  category_id: '',
  description: '',
  picture: '',
  variations: []
})

const categories = ref([])
const loading = ref(false)
const loaded = ref(false)
const saving = ref(false)
const deleting = ref(false)
const success = ref(false)
const error = ref('')

const pictureFile = ref(null)
const previewUrl = ref(null)

const resolvePicture = pic => pic ? `http://127.0.0.1:8000/storage/products/${pic}` : null

async function fetchCategories() {
  try {
    const res = await adminApi.get('/categories')
    categories.value = Array.isArray(res.data) ? res.data : (res.data?.data ?? [])
  } catch {}
}

async function fetchProduct() {
  if (!id) return router.replace({ name: 'admindashboard' })
  loading.value = true
  try {
    const res = await adminApi.get(`/products/${id}`)
    const data = res.data?.data ?? res.data
    if (!data?.id) return router.replace({ name: 'products' })

    form.name = data.name || ''
    form.price = parseFloat(data.price) || 0
    form.category_id = data.category_id || ''
    form.description = data.description || ''
    form.picture = data.picture || ''

    form.variations = data.variations?.map(v => ({
      color: v.color || '',
      size: v.size || '',
      stock: v.stock || 0
    })) || []

    loaded.value = true
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Failed to load product.'
  } finally {
    loading.value = false
  }
}

function onFileChange(e) {
  const file = e.target.files?.[0]
  if (!file) return
  pictureFile.value = file
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  previewUrl.value = URL.createObjectURL(file)
}

function clearFile() {
  pictureFile.value = null
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  previewUrl.value = null
  form.picture = ''
}

function addVariation() {
  form.variations.push({ color: '', size: '', stock: 0 })
}

function removeVariation(index) {
  form.variations.splice(index, 1)
}

function validate() {
  error.value = ''
  if (!form.name.trim()) { error.value = 'Name required'; return false }
  if (!form.price || isNaN(form.price)) { error.value = 'Price required'; return false }
  if (!form.category_id) { error.value = 'Category required'; return false }
  for (let v of form.variations) {
    if (!v.color || !v.size || v.stock === null || isNaN(v.stock)) {
      error.value = 'All variations must have color, size, and stock'
      return false
    }
  }
  return true
}

async function submit() {
  if (!validate()) return
  saving.value = true
  const fd = new FormData()
  fd.append('name', form.name)
  fd.append('price', String(form.price))
  fd.append('category_id', String(form.category_id))
  if (form.description) fd.append('description', form.description)
  if (pictureFile.value) fd.append('picture', pictureFile.value)
  fd.append('variations', JSON.stringify(form.variations))
  fd.append('_method', 'PUT')
  try {
    await adminApi.post(`/products/${id}`, fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    success.value = true
    setTimeout(() => router.push({ name: 'admindashboard' }), 700)
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Update failed'
  } finally {
    saving.value = false
  }
}

async function confirmDelete() {
  if (!window.confirm('Delete this product?')) return
  deleting.value = true
  try {
    await adminApi.delete(`/products/${id}`)
    router.push({ name: 'admindashboard' })
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Delete failed'
  } finally {
    deleting.value = false
  }
}

function cancel() { router.back() }

onMounted(() => { fetchCategories(); fetchProduct() })
</script>

<style scoped>
.form-container {
  max-width: 900px;
  margin: 32px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 18px rgba(0,0,0,0.04);
}

.product-form {
  display: flex;
  flex-direction: column;
}

label {
  margin-top: 12px;
  font-weight: 600;
}

input, select, textarea {
  margin-top: 6px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 1rem;
}

textarea { min-height: 120px; }

.preview-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
}

.preview {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #e6e7ea;
}

.variations-section {
  margin-top: 20px;
}

.variation-row {
  display: flex;
  gap: 8px;
  margin-bottom: 8px;
}

.variation-row input {
  flex: 1;
}

.buttons {
  display: flex;
  gap: 8px;
  margin-top: 16px;
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
}

.success {
  margin-top: 12px;
  color: #16a34a;
}

.error {
  margin-top: 12px;
  color: #dc2626;
}
</style>
