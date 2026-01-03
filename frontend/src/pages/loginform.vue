<template>
  <div class="signin-page">
    <div class="signin-card">
      
      <div class="brand-header" @click="$emit('go-home')">
        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
        </svg>
        <h1>Clothing Store<span class="dot">.</span></h1>
      </div>

      <div class="form-header">
        <h2>Log In</h2>
        <p>Enter your credentials below.</p>
      </div>

      <form @submit.prevent="handleSignIn" class="signin-form">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="you@example.com"
            required
            class="form-input"
            :class="{ 'input-error': errors.email }"
          />
          <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-input">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
              class="form-input"
              :class="{ 'input-error': errors.password }"
            />
            <button
              type="button"
              class="password-toggle"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? 'Hide password' : 'Show password'"
            >
              {{ showPassword ? '🙈' : '👁️' }}
            </button>
          </div>
          <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
        </div>

        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.rememberMe" />
            Remember me
          </label>
          <a href="#" class="forgot-link" @click.prevent="$emit('forgot-password')">
            Forgot password?
          </a>
        </div>

        <button type="submit" class="signin-btn" :disabled="loading">
          <span v-if="loading">Signing In...</span>
          <span v-else>Sign In</span>
        </button>
      </form>

      <p class="signup-link">
        Don't have an account? 
        <router-link to="/signup">Sign Up</router-link>
      </p>

      <router-link to="/" class="back-home-link">← Back to Home</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'

const loading = ref(false)
const showPassword = ref(false)

const form = reactive({
  email: '',
  password: '',
  rememberMe: false
})

const errors = reactive({
  email: '',
  password: ''
})

const router = useRouter()

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
  }

  return isValid
}

async function handleSignIn() {
  if (!validateForm()) return

  loading.value = true

  try {
    const res = await api.post('/login', {
      email: form.email,
      password: form.password
    })

    if (res.data.token) {
      const token = res.data.token
      // store token + user
      localStorage.setItem('auth_token', token)
      if (res.data.user) {
        localStorage.setItem('user', JSON.stringify(res.data.user))
      }

      // Immediately set axios default header so subsequent requests include the token
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`

      // Optional: pre-warm /me to ensure token works before route change.
      // This prevents the dashboard mounting and calling /me without the header.
      try {
        await api.get('/me') // if this fails with 401 it will be caught below
      } catch (e) {
        // If /me fails here, the response interceptor will clear token and redirect.
        // Show the error message to the user too:
        console.error('Pre-check /me failed:', e)
        errors.email = e.response?.data?.message || 'Login succeeded but token validation failed'
        loading.value = false
        return
      }

      // Navigate to user dashboard
      router.push('/userdashboard')
    } else {
      errors.email = res.data.message || 'Login failed'
    }
  } catch (err) {
    console.error('Login error:', err)
    
    if (err.response) {
      errors.email = err.response.data?.message || 'Invalid credentials'
    } else if (err.request) {
      errors.email = 'Network error. Please try again.'
    } else {
      errors.email = 'Something went wrong.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.signin-page {
  min-height: 100vh;
  background: #f0f4f8;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 5rem;
}

.signin-card {
  max-width: 400px;
  width: 100%;
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  padding: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.brand-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  cursor: pointer;
}

.brand-header h1 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #1e293b;
  margin: 0;
}

.dot {
  color: #4f46e5;
}

.form-header {
  text-align: center;
}

.form-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 0.5rem;
}

.form-header p {
  color: #64748b;
  font-size: 0.95rem;
  margin: 0;
}

.signin-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 600;
  color: #334155;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.form-input {
  padding: 0.75rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s;
  width: 100%;
}

.form-input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-input.input-error {
  border-color: #ef4444;
}

.error-message {
  color: #ef4444;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

.password-input {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 0;
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #334155;
  cursor: pointer;
}

.forgot-link {
  color: #4f46e5;
  text-decoration: none;
  font-weight: 500;
}

.forgot-link:hover {
  text-decoration: underline;
}

.signin-btn {
  background: #4f46e5;
  color: white;
  border: none;
  padding: 0.875rem 1.5rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 0.5rem;
}

.signin-btn:hover:not(:disabled) {
  background: #4338ca;
}

.signin-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.signup-link, .back-home-link {
  text-align: center;
  font-size: 0.9rem;
  color: #64748b;
}

.signup-link a {
  color: #4f46e5;
  text-decoration: none;
  font-weight: 600;
}

.signup-link a:hover {
  text-decoration: underline;
}

.back-home-link {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
  text-decoration: none;
  font-weight: 500;
}

.back-home-link:hover {
  color: #334155;
}
</style>