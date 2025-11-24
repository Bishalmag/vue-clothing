<template>
  <div class="signin-page">
    <div class="signin-container">
      <!-- Left side - Brand/Image -->
      <div class="signin-left">
        <div class="brand-section">
          <div class="brand-logo" @click="$emit('go-home')">
            <svg viewBox="0 0 24 24" width="40" height="40">
              <path d="M3 12a9 9 0 1018 0 9 9 0 10-18 0z" fill="currentColor" opacity="0.08"/>
              <path d="M12 6l4 8H8l4-8z" fill="currentColor"/>
            </svg>
          </div>
          <h1>Clothify</h1>
          <p class="brand-tagline">Everyday fashion for everyone</p>
        </div>
        
        <div class="feature-image">
          <div class="image-placeholder">
            <svg viewBox="0 0 200 200" width="200" height="200">
              <path d="M100 40l30 60-30 60-30-60 30-60z" fill="#667eea" opacity="0.1"/>
              <circle cx="100" cy="100" r="40" fill="#667eea" opacity="0.08"/>
              <path d="M80 80l40 40M120 80l-40 40" stroke="#667eea" stroke-width="2" fill="none"/>
            </svg>
          </div>
          <h3>Welcome Back!</h3>
          <p>Sign in to explore the latest fashion trends and manage your account.</p>
        </div>
      </div>

      <!-- Right side - Form -->
      <div class="signin-right">
        <div class="signin-form-container">
          <div class="form-header">
            <h2>Sign In</h2>
            <p>Enter your credentials to access your account</p>
          </div>

          <form @submit.prevent="handleSignIn" class="signin-form">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Enter your email"
                required
                class="form-input"
                :class="{ error: errors.email }"
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
                  placeholder="Enter your password"
                  required
                  class="form-input"
                  :class="{ error: errors.password }"
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
                <span class="checkmark"></span>
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
            <p class="signup-link">
              Don't have an account? 
              <router-link to="/signup">Sign Up Here</router-link>
            </p>
            <p class="back-link">
            <router-link to="/">← Back to Home</router-link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

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
  } else if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters'
    isValid = false
  }

  return isValid
}

async function handleSignIn() {
  if (!validateForm()) return

  loading.value = true

  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 1000))

    // Redirect to homepage
    router.push('/userdashboard')
  } catch (error) {
    console.error('Sign in failed:', error)
    errors.email = 'Invalid credentials. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
<style scoped>
.signin-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f6f8fb 0%, #f1f5f9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.signin-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  max-width: 1000px;
  width: 100%;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(16, 24, 40, 0.08);
  overflow: hidden;
  min-height: 600px;
}

/* Left Side */
.signin-left {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 3rem 2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.brand-section {
  text-align: center;
}

.brand-logo {
  width: 60px;
  height: 60px;
  margin: 0 auto 1rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-logo svg {
  color: white;
}

.brand-section h1 {
  font-size: 2rem;
  margin: 0 0 0.5rem 0;
  font-weight: 700;
}

.brand-tagline {
  opacity: 0.9;
  margin: 0;
}

.feature-image {
  text-align: center;
  margin-top: 2rem;
}

.image-placeholder {
  margin-bottom: 1.5rem;
}

.feature-image h3 {
  font-size: 1.5rem;
  margin: 0 0 1rem 0;
}

.feature-image p {
  opacity: 0.8;
  line-height: 1.6;
}

/* Right Side */
.signin-right {
  padding: 3rem 2.5rem;
  display: flex;
  align-items: center;
}

.signin-form-container {
  width: 100%;
  max-width: 400px;
  margin: 0 auto;
}

.form-header {
  text-align: center;
  margin-bottom: 2rem;
}

.form-header h2 {
  font-size: 2rem;
  color: #1f2d3d;
  margin: 0 0 0.5rem 0;
  font-weight: 700;
}

.form-header p {
  color: #6b7280;
  margin: 0;
}

/* Form Styles */
.signin-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
}

.form-input {
  padding: 0.75rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s ease;
  background: white;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input.error {
  border-color: #ef4444;
}

.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.password-input {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  padding: 0;
}

/* Form Options */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.checkbox-label input {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.checkbox-label input:checked + .checkmark {
  background: #667eea;
  border-color: #667eea;
}

.checkbox-label input:checked + .checkmark::after {
  content: '✓';
  color: white;
  font-size: 12px;
}

.forgot-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.forgot-link:hover {
  text-decoration: underline;
}

/* Sign In Button */
.signin-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 0.875rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.signin-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.signin-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Sign Up Link */
.signup-link {
  text-align: center;
  color: #6b7280;
  margin-top: 1.5rem;
}

.signup-link a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.signup-link a:hover {
  text-decoration: underline;
}

/* Mobile */
@media (max-width: 768px) {
  .signin-container {
    grid-template-columns: 1fr;
  }
  
  .signin-left {
    display: none;
  }
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