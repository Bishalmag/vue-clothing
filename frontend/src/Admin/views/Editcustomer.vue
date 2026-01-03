<template>
  <div class="edit-customer-container">
    <h2>Edit User</h2>

    <!-- Loading / Error -->
    <p v-if="loading" class="loading">Loading user...</p>
    <p v-if="error" class="error">{{ error }}</p>

    <!-- Form -->
    <form v-if="loaded" @submit.prevent="updateUser" class="edit-form">
      <div class="form-group">
        <label>First Name</label>
        <input v-model="form.first_name" type="text" />
        <span class="error-text" v-if="errors.first_name">{{ errors.first_name }}</span>
      </div>

      <div class="form-group">
        <label>Last Name</label>
        <input v-model="form.last_name" type="text" />
        <span class="error-text" v-if="errors.last_name">{{ errors.last_name }}</span>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" />
        <span class="error-text" v-if="errors.email">{{ errors.email }}</span>
      </div>

      <div class="form-group">
        <label>Password (leave blank to keep current)</label>
        <input v-model="form.password" type="password" />
        <span class="error-text" v-if="errors.password">{{ errors.password }}</span>
      </div>

      <div class="form-actions">
        <button type="submit" :disabled="saving">{{ saving ? 'Saving...' : 'Save' }}</button>
        <button type="button" @click="cancel">Cancel</button>
        <button type="button" class="delete-btn" @click="deleteUser" :disabled="deleting">
          {{ deleting ? 'Deleting...' : 'Delete' }}
        </button>
      </div>

      <p v-if="success" class="success-text">User updated successfully!</p>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import adminApi from '@/api/adminaxois'

/* -------------------------------
   Router
-------------------------------- */
const route = useRoute()
const router = useRouter()
const userId = route.params.id

/* -------------------------------
   State
-------------------------------- */
const form = reactive({
  first_name: '',
  last_name: '',
  email: '',
  password: ''
})

const errors = reactive({
  first_name: '',
  last_name: '',
  email: '',
  password: ''
})

const loading = ref(false)
const loaded = ref(false)
const saving = ref(false)
const deleting = ref(false)
const success = ref(false)
const error = ref('')

/* -------------------------------
   Fetch User
-------------------------------- */
async function fetchUser() {
  if (!userId) {
    error.value = 'User ID missing. Redirecting to list...'
    setTimeout(() => router.replace({ name: 'customers' }).catch(() => {}), 500)
    return
  }

  loading.value = true
  error.value = ''

  try {
    const res = await adminApi.get(`/users/${userId}`)
    const user = res.data

    form.first_name = user.first_name ?? ''
    form.last_name = user.last_name ?? ''
    form.email = user.email ?? ''
    loaded.value = true
  } catch (err) {
    console.error('Fetch user error', err)
    if (err.response?.status === 404) {
      error.value = 'User not found. Redirecting...'
      setTimeout(() => router.replace({ name: 'customers' }).catch(() => {}), 800)
    } else {
      error.value = err.response?.data?.message || err.message || 'Failed to load user.'
    }
  } finally {
    loading.value = false
  }
}

/* -------------------------------
   Validate Form
-------------------------------- */
function validate() {
  errors.first_name = ''
  errors.last_name = ''
  errors.email = ''
  let ok = true

  if (!form.first_name.trim()) {
    errors.first_name = 'First name is required.'
    ok = false
  }
  if (!form.last_name.trim()) {
    errors.last_name = 'Last name is required.'
    ok = false
  }
  if (!form.email.trim()) {
    errors.email = 'Email is required.'
    ok = false
  }

  return ok
}

/* -------------------------------
   Update User
-------------------------------- */
async function updateUser() {
  if (!validate()) return
  if (!userId) return

  saving.value = true
  error.value = ''
  success.value = false

  try {
    const payload = {
      first_name: form.first_name,
      last_name: form.last_name,
      email: form.email
    }

    if (form.password && form.password.trim()) {
      payload.password = form.password
    }

    await adminApi.put(`/users/${userId}`, payload)

    success.value = true
    setTimeout(() => router.push({ name: 'customers' }).catch(() => {}), 700)
  } catch (err) {
    console.error('Update user error', err)
    if (err.response?.status === 422) {
      const v = err.response.data?.errors
      if (v?.first_name) errors.first_name = v.first_name[0]
      if (v?.last_name) errors.last_name = v.last_name[0]
      if (v?.email) errors.email = v.email[0]
      error.value = err.response.data?.message || 'Validation failed.'
    } else if (err.response?.status === 401) {
      error.value = 'Unauthorized. Please login again.'
      setTimeout(() => router.replace({ name: 'adminlogin' }).catch(() => {}), 600)
    } else {
      error.value = err.response?.data?.message || err.message || 'Failed to update user.'
    }
  } finally {
    saving.value = false
  }
}

/* -------------------------------
   Delete User
-------------------------------- */
async function deleteUser() {
  if (!userId) return

  const confirmed = window.confirm('Are you sure you want to delete this user? This action cannot be undone.')
  if (!confirmed) return

  deleting.value = true
  error.value = ''

  try {
    await adminApi.delete(`/users/${userId}`)
    router.push({ name: 'customers' }).catch(() => {})
  } catch (err) {
    console.error('Delete user error', err)
    if (err.response?.status === 401) {
      error.value = 'Unauthorized. Please login again.'
      setTimeout(() => router.replace({ name: 'adminlogin' }).catch(() => {}), 600)
    } else {
      error.value = err.response?.data?.message || err.message || 'Failed to delete user.'
    }
  } finally {
    deleting.value = false
  }
}

/* -------------------------------
   Cancel / Back
-------------------------------- */
function cancel() {
  router.back()
}

/* -------------------------------
   Init
-------------------------------- */
onMounted(fetchUser)
</script>

<style scoped>
.edit-customer-container {
  max-width: 700px;
  margin: 40px auto;
  padding: 25px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

.edit-form .form-group {
  margin-bottom: 15px;
}

.edit-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.edit-form input {
  width: 100%;
  padding: 8px 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.form-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.form-actions button {
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: #fff;
}

.form-actions button[type="submit"] {
  background: #28a745;
}

.form-actions button.delete-btn {
  background: #dc3545;
}

.form-actions button[type="button"]:not(.delete-btn) {
  background: #6c757d;
}

.error-text {
  color: #dc3545;
  font-size: 13px;
}

.success-text {
  color: #28a745;
  font-weight: bold;
}

.loading {
  text-align: center;
  font-weight: bold;
}

.error {
  text-align: center;
  color: red;
  margin-bottom: 10px;
}
</style>
