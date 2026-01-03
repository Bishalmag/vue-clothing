<template>
  <div class="form-container">
    <h2>Add Product</h2>

    <form @submit.prevent="submit" class="product-form" enctype="multipart/form-data">
      <!-- Product Info -->
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
      <div v-if="previewUrl" class="preview-wrap">
        <img :src="previewUrl" alt="preview" class="preview" />
        <button type="button" class="btn small" @click="clearFile">Remove</button>
      </div>

      <!-- Product Variations -->
      <div class="variations-section">
        <h3>Variations</h3>
        <div v-for="(v, index) in form.variations" :key="index" class="variation-row">
          <input v-model="v.color" type="text" placeholder="Color" required />

          <select v-model="v.size" required>
            <option value="">--Select Size--</option>
            <option v-for="s in sizes" :key="s" :value="s">{{ s }}</option>
          </select>

          <input v-model.number="v.stock" type="number" min="0" placeholder="Stock" required />

          <button type="button" class="btn small danger" @click="removeVariation(index)">Remove</button>
        </div>
        <button type="button" class="btn small" @click="addVariation">+ Add Variation</button>
      </div>

      <!-- Description -->
      <label>Description</label>
      <textarea v-model="form.description" placeholder="Description (optional)"></textarea>

      <!-- Buttons -->
      <div class="buttons">
        <button type="submit" class="btn primary" :disabled="saving">
          <span v-if="saving">Saving...</span>
          <span v-else>Add Product</span>
        </button>
        <button type="button" class="btn" @click="cancel" :disabled="saving">Cancel</button>
      </div>

      <!-- Feedback -->
      <p v-if="success" class="success">Product created successfully.</p>
      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import adminApi from '@/api/adminaxois'

const router = useRouter()

// Sizes dropdown
const sizes = ['S', 'M', 'L', 'XL']

// Form reactive object
const form = reactive({
  name: '',
  price: null,
  category_id: '',
  description: '',
  variations: [
    { color: '', size: '', stock: 0 }
  ]
})

const categories = ref([])
const pictureFile = ref(null)
const previewUrl = ref(null)
const saving = ref(false)
const success = ref(false)
const error = ref('')

// Fetch categories from API
async function fetchCategories() {
  try {
    const res = await adminApi.get('/categories')
    categories.value = Array.isArray(res.data) ? res.data : (res.data.data ?? [])
  } catch (err) {
    console.error('Fetch categories error', err)
  }
}

// File handling
function onFileChange(e) {
  const file = e.target.files?.[0]
  if (!file) return
  pictureFile.value = file
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  previewUrl.value = URL.createObjectURL(file)
}

function clearFile() {
  pictureFile.value = null
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
  }
}

// Variations handling
function addVariation() {
  form.variations.push({ color: '', size: '', stock: 0 })
}
function removeVariation(index) {
  form.variations.splice(index, 1)
}

// Form validation
function validate() {
  error.value = ''
  if (!form.name.trim()) return error.value = 'Product name is required.', false
  if (form.price === null || isNaN(form.price)) return error.value = 'Price is required and must be a number.', false
  if (!form.category_id) return error.value = 'Category is required.', false
  if (!form.variations.length) return error.value = 'At least one variation is required.', false
  for (const v of form.variations) {
    if (!v.color.trim() || !v.size.trim() || v.stock < 0) {
      return error.value = 'All variations must have color, size, and valid stock.', false
    }
  }
  return true
}

// Submit form
async function submit() {
  success.value = false
  error.value = ''
  if (!validate()) return

  saving.value = true
  try {
    const fd = new FormData()
    fd.append('name', form.name)
    fd.append('price', String(form.price))
    fd.append('category_id', String(form.category_id))
    if (form.description) fd.append('description', form.description)
    if (pictureFile.value) fd.append('picture', pictureFile.value)
    fd.append('variations', JSON.stringify(form.variations))

    await adminApi.post('/products', fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    success.value = true
    setTimeout(() => router.push({ name: 'allproducts' }), 700)
  } catch (err) {
    console.error('Add product error', err)
    error.value = err.response?.data?.message || err.message || 'Failed to add product.'
  } finally {
    saving.value = false
  }
}

function cancel() {
  router.back()
}

onMounted(() => {
  fetchCategories()
})
</script>

<style scoped>
/* Component-specific styles */
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

.product-form label {
  margin-top: 12px;
  font-weight: 600;
}

.product-form input,
.product-form select,
.product-form textarea {
  margin-top: 6px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
}

.product-form textarea {
  min-height: 120px;
}

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
  margin-bottom: 10px;
}

.variation-row input,
.variation-row select {
  flex: 1;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
}

.variation-row .danger {
  background: #dc2626;
  color: #fff;
  border-color: transparent;
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

.success {
  color: #16a34a;
  margin-top: 10px;
}

.error {
  color: #dc2626;
  margin-top: 10px;
}
</style>
