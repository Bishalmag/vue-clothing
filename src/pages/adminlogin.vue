<template>
  <div class="admin-login-page">
    <div class="admin-login-container">
      <div class="admin-login-form">
        <div class="form-header">
          <h2>Admin Login</h2>
          <p>Access the administration panel</p>
        </div>

        <form @submit.prevent="handleAdminLogin" class="login-form">
          <div class="form-group">
            <input
              v-model="form.email"
              type="email"
              placeholder="Admin Email"
              required
              class="form-input"
              :class="{ error: errors.email }"
            />
            <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
          </div>

          <div class="form-group">
            <input
              v-model="form.password"
              type="password"
              placeholder="Password"
              required
              class="form-input"
              :class="{ error: errors.password }"
            />
            <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
          </div>

          <button type="submit" class="login-btn" :disabled="loading">
            <span v-if="loading">Signing In...</span>
            <span v-else>Sign In</span>
          </button>
        </form>

        <div class="demo-credentials">
          <p><strong>Demo Credentials:</strong></p>
          <p>Email: admin@example.com</p>
          <p>Password: admin123</p>
        </div>

        <p class="back-link">
          <router-link to="/">← Back to Home</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(false)

const form = reactive({
  email: '',
  password: ''
})

const errors = reactive({
  email: '',
  password: ''
})

function validateForm() {
  let isValid = true
  
  errors.email = ''
  errors.password = ''

  if (!form.email) {
    errors.email = 'Email is required'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!form.password) {
    errors.password = 'Password is required'
    isValid = false
  } else if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters'
    isValid = false
  }

  return isValid
}

async function handleAdminLogin() {
  if (!validateForm()) return

  loading.value = true

  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1000))

    // Check admin credentials
    if (form.email === 'admin@example.com' && form.password === 'admin123') {
      // Store admin authentication
      localStorage.setItem('admin', 'true')
      localStorage.setItem('adminUser', JSON.stringify({
        name: 'Administrator',
        email: form.email
      }))
      
      // Redirect to admin dashboard
      router.push('/admindashboard')
    } else {
      errors.email = 'Invalid admin credentials. Please try again.'
    }
  } catch (error) {
    console.error('Admin login failed:', error)
    errors.email = 'Login failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.admin-login-page {
  min-height: 100vh;
  background: #f8fafc;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.admin-login-container {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  width: 100%;
  max-width: 400px;
}

.form-header {
  text-align: center;
  margin-bottom: 2rem;
}

.form-header h2 {
  font-size: 1.5rem;
  color: #1f2937;
  margin: 0 0 0.5rem 0;
  font-weight: 600;
}

.form-header p {
  color: #6b7280;
  margin: 0;
  font-size: 0.9rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
}

.form-input.error {
  border-color: #ef4444;
}

.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.login-btn {
  background: #1f2937;
  color: white;
  border: none;
  padding: 0.75rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  margin-top: 1rem;
}

.login-btn:hover:not(:disabled) {
  background: #374151;
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.demo-credentials {
  background: #f3f4f6;
  padding: 1rem;
  border-radius: 6px;
  margin: 1.5rem 0;
  font-size: 0.875rem;
  color: #4b5563;
}

.demo-credentials p {
  margin: 0.25rem 0;
}

.back-link {
  text-align: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.back-link a {
  color: #6b7280;
  text-decoration: none;
  font-size: 0.9rem;
}

.back-link a:hover {
  color: #374151;
}
</style>