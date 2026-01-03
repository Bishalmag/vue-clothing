<template>
  <div class="container">
    <h2>Add Category</h2>

    <form @submit.prevent="submit">
      <label>Name *</label>
      <input
        type="text"
        v-model="form.name"
        required
        placeholder="Enter category name"
      />

      <label>Description</label>
      <textarea
        v-model="form.description"
        placeholder="Enter description (optional)"
      ></textarea>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Saving...' : 'Add Category' }}
      </button>
    </form>

    <p v-if="success" class="success">Category added successfully</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useLocalStorage } from '@/composables/useLocalStorage'
import adminApi from '@/api/adminaxois'

const form = ref({ name: '', description: '' })
const loading = ref(false)
const success = ref(false)
const error = ref('')

// Use reactive localStorage token
const adminToken = useLocalStorage('adminToken', null)

const submit = async () => {
  loading.value = true
  success.value = false
  error.value = ''

  if (!adminToken.value) {
    error.value = 'Admin token not found. Please login first.'
    loading.value = false
    return
  }

  if (!form.value.name.trim()) {
    error.value = 'Name is required.'
    loading.value = false
    return
  }

  try {
    await adminApi.post('/categories', form.value)

    success.value = true
    form.value = { name: '', description: '' }
  } catch (e) {
    console.error(e)
    error.value = `Add category failed: ${e.response?.data?.message || e.message}`
  } finally {
    loading.value = false
  }
}

</script>



<style scoped>
.container {
  max-width: 500px;
  margin: 60px auto;
  padding: 20px;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

label {
  display: block;
  margin: 12px 0 6px;
  font-weight: 500;
}

input,
textarea {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
}

textarea {
  min-height: 100px;
}

button {
  width: 100%;
  margin-top: 18px;
  padding: 10px;
  border-radius: 6px;
  background: #2563eb;
  color: white;
  font-weight: 600;
}

button:disabled {
  opacity: 0.6;
}

.success {
  margin-top: 15px;
  color: #16a34a;
  text-align: center;
}

.error {
  margin-top: 15px;
  color: #dc2626;
  text-align: center;
}
</style>
