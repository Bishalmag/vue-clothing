<script setup>
import { ref, onMounted } from 'vue'
import adminApi from '@/api/adminaxois' // Use the reactive Axios instance
import { useLocalStorage } from '@/composables/useLocalStorage'

// Reactive token from localStorage composable
const adminToken = useLocalStorage('adminToken', '')

const admin = ref({ username: '', email: '', role: '' })
const loading = ref(false)
const error = ref('')

// Fetch admin profile
async function fetchAdminProfile() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminApi.get('/me')
    admin.value = res.data
  } catch (err) {
    console.error(err)
    error.value = err.response?.data?.message || err.message || 'Failed to load profile'
  } finally {
    loading.value = false
  }
}

// Run on component mount
onMounted(() => {
  if (!adminToken.value) {
    // Optional: redirect if token missing
    window.location.href = '/adminlogin'
  } else {
    fetchAdminProfile()
  }
})
</script>

<template>
  <div class="profile-container">
    <h2>Admin Profile</h2>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="profile-info">
      <p><strong>Name:</strong> {{ admin.username || '—' }}</p>
      <p><strong>Email:</strong> {{ admin.email || '—' }}</p>
      <p><strong>Role:</strong> {{ admin.role || '—' }}</p>
    </div>
  </div>
</template>

<style scoped>
.profile-container {
  max-width: 500px;
  margin: 2rem auto;
  padding: 1.5rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  text-align: left;
}

h2 {
  margin-bottom: 1rem;
  color: #111827;
}

.loading,
.error {
  font-size: 1rem;
  color: #6b7280;
  margin-top: 1rem;
}

.error {
  color: #ef4444;
}

.profile-info p {
  font-size: 1rem;
  margin: 0.5rem 0;
  color: #374151;
}
</style>
