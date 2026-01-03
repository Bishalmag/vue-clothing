<template>
  <div class="edit-container">
    <h2>Edit Category</h2>

    <div v-if="loading && !loaded" class="loading">Loading category…</div>

    <form v-else @submit.prevent="submit" class="edit-form">
      <label>Name *</label>
      <input
        type="text"
        v-model="form.name"
        required
        placeholder="Category name"
        :class="{ invalid: errors.name }"
      />
      <span v-if="errors.name" class="error-message">{{ errors.name }}</span>

      <label>Description</label>
      <textarea
        v-model="form.description"
        placeholder="Description (optional)"
      ></textarea>

      <div class="buttons">
        <button type="submit" class="btn primary" :disabled="saving">
          <span v-if="saving">Saving...</span>
          <span v-else>Save Changes</span>
        </button>

        <button type="button" class="btn" @click="cancel" :disabled="saving">
          Cancel
        </button>

        <button
          v-if="showDelete"
          type="button"
          class="btn danger"
          @click="confirmDelete"
          :disabled="deleting"
        >
          <span v-if="deleting">Deleting...</span>
          <span v-else>Delete</span>
        </button>
      </div>

      <p v-if="success" class="success">Category updated successfully.</p>
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

// route.params may be string or undefined — capture current id
const id = route.params.id

const form = reactive({
  name: '',
  description: ''
})

const errors = reactive({
  name: ''
})

const loading = ref(false)
const loaded = ref(false)
const saving = ref(false)
const deleting = ref(false)
const success = ref(false)
const error = ref('')
const showDelete = ref(true) // toggle if you want to hide delete in some cases

async function fetchCategory() {
  // if id is missing, go back to list (avoid staying on a broken page)
  if (!id) {
    error.value = 'Category id not provided.'
    // replace so user can't press back into invalid route
    router.replace({ name: 'allcategories' }).catch(() => {})
    return
  }

  loading.value = true
  error.value = ''
  try {
    // Correct endpoint: /api/categories/{id}
    console.log(`[Editcategory] requesting /categories/${id}`)
    const res = await adminApi.get(`/categories/${id}`)
    console.log('[Editcategory] response', res)

    // Accept either raw object or { data: { ... } } shapes
    const data = res.data?.data ?? res.data
    if (!data || !data.id) {
      error.value = 'Category not found.'
      // redirect to list after brief pause
      setTimeout(() => router.replace({ name: 'allcategories' }).catch(() => {}), 800)
      return
    }

    form.name = data.name ?? ''
    form.description = data.description ?? ''
    loaded.value = true
  } catch (err) {
    console.error('Fetch category error', err)
    if (err.response?.status === 404) {
      error.value = 'Category not found (404). It may have been deleted.'
      setTimeout(() => router.replace({ name: 'allcategories' }).catch(() => {}), 900)
    } else if (err.response?.status === 401) {
      // unauthorized — token missing/invalid — redirect to admin login
      error.value = 'Unauthorized. Please sign in again.'
      setTimeout(() => router.replace({ name: 'adminlogin' }).catch(() => {}), 600)
    } else {
      error.value = err.response?.data?.message || err.message || 'Failed to load category.'
    }
  } finally {
    loading.value = false
  }
}

function validate() {
  errors.name = ''
  let ok = true
  if (!form.name || !form.name.toString().trim()) {
    errors.name = 'Name is required.'
    ok = false
  } else if (form.name.toString().length > 255) {
    errors.name = 'Name must be 255 characters or less.'
    ok = false
  }
  return ok
}

async function submit() {
  success.value = false
  error.value = ''

  if (!validate()) return

  if (!id) {
    error.value = 'Category id missing. Cannot save.'
    return
  }

  saving.value = true
  try {
    // Backend expects { name, description }
    await adminApi.put(`/categories/${id}`, {
      name: form.name,
      description: form.description
    })
    success.value = true
    // navigate back to categories list after short delay so user sees success
    setTimeout(() => {
      router.push({ name: 'allcategories' }).catch(() => {})
    }, 700)
  } catch (err) {
    console.error('Update category error', err)
    if (err.response?.status === 422) {
      // validation errors from backend
      const v = err.response.data?.errors
      if (v?.name) errors.name = v.name[0]
      error.value = err.response.data?.message || 'Validation failed.'
    } else if (err.response?.status === 401) {
      error.value = 'Unauthorized. Please sign in again.'
      setTimeout(() => router.replace({ name: 'adminlogin' }).catch(() => {}), 600)
    } else {
      error.value = err.response?.data?.message || err.response?.data?.error || err.message || 'Failed to update category.'
    }
  } finally {
    saving.value = false
  }
}

function cancel() {
  router.back()
}

async function confirmDelete() {
  const confirmed = window.confirm('Are you sure you want to delete this category? This action cannot be undone.')
  if (!confirmed) return
  if (!id) {
    error.value = 'Category id missing. Cannot delete.'
    return
  }
  deleting.value = true
  try {
    await adminApi.delete(`/categories/${id}`)
    // navigate back to categories list
    router.push({ name: 'allcategories' }).catch(() => {})
  } catch (err) {
    console.error('Delete category error', err)
    if (err.response?.status === 401) {
      error.value = 'Unauthorized. Please sign in again.'
      setTimeout(() => router.replace({ name: 'adminlogin' }).catch(() => {}), 600)
    } else {
      error.value = err.response?.data?.message || err.message || 'Failed to delete category.'
    }
  } finally {
    deleting.value = false
  }
}

onMounted(() => {
  fetchCategory()
})
</script>

<style scoped>
.edit-container {
  max-width: 700px;
  margin: 40px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

h2 {
  margin: 0 0 16px;
  font-size: 1.4rem;
  color: #111827;
}

.edit-form {
  display: flex;
  flex-direction: column;
}

label {
  margin-top: 12px;
  font-weight: 600;
  color: #374151;
}

input,
textarea {
  margin-top: 6px;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 1rem;
}

textarea {
  min-height: 120px;
}

input.invalid {
  border-color: #ef4444;
}

.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 6px;
}

.buttons {
  display: flex;
  gap: 10px;
  margin-top: 16px;
  align-items: center;
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