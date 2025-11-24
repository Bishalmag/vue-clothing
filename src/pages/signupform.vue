<template>
  <div class="signup-page">
    <div class="signup-container">
      <!-- Left side - Brand/Image -->
      <div class="signup-left">
        <div class="brand-section">
          <div class="brand-logo" @click="$emit('go-home')">
            <svg viewBox="0 0 24 24" width="40" height="40">
              <path d="M3 12a9 9 0 1018 0 9 9 0 10-18 0z" fill="currentColor" opacity="0.08"/>
              <path d="M12 6l4 8H8l4-8z" fill="currentColor"/>
            </svg>
          </div>
          <h1>Clothify</h1>
          <p class="brand-tagline">Join our fashion community</p>
        </div>
        
        <div class="feature-image">
          <div class="image-placeholder">
            <svg viewBox="0 0 200 200" width="200" height="200">
              <path d="M60 80l40 40 40-40M60 120l40 40 40-40" stroke="#667eea" stroke-width="3" fill="none" stroke-linecap="round"/>
              <circle cx="100" cy="100" r="45" fill="#667eea" opacity="0.08"/>
            </svg>
          </div>
          <h3>Create Your Account</h3>
          <p>Join thousands of fashion enthusiasts and discover your perfect style.</p>
          
          <div class="benefits">
            <div class="benefit-item">
              <span class="benefit-icon">🎯</span>
              <span>Personalized recommendations</span>
            </div>
            <div class="benefit-item">
              <span class="benefit-icon">🚚</span>
              <span>Free shipping on first order</span>
            </div>
            <div class="benefit-item">
              <span class="benefit-icon">⭐</span>
              <span>Exclusive member deals</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right side - Form -->
      <div class="signup-right">
        <div class="signup-form-container">
          <div class="form-header">
            <h2>Create Account</h2>
            <p>Fill in your details to get started</p>
          </div>

          <form @submit.prevent="handleSignUp" class="signup-form">
            <div class="name-fields">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input
                  id="firstName"
                  v-model="form.firstName"
                  type="text"
                  placeholder="Bishal"
                  required
                  class="form-input"
                  :class="{ error: errors.firstName }"
                />
                <span v-if="errors.firstName" class="error-message">{{ errors.firstName }}</span>
              </div>

              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input
                  id="lastName"
                  v-model="form.lastName"
                  type="text"
                  placeholder="Gharti"
                  required
                  class="form-input"
                  :class="{ error: errors.lastName }"
                />
                <span v-if="errors.lastName" class="error-message">{{ errors.lastName }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="abc@example.com"
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
                  placeholder="Create a strong password"
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
              
              <div class="password-strength">
                <div class="strength-bar" :class="passwordStrength"></div>
                <span class="strength-text">{{ strengthText }}</span>
              </div>
            </div>

            <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <div class="password-input">
                <input
                  id="confirmPassword"
                  v-model="form.confirmPassword"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  placeholder="Confirm your password"
                  required
                  class="form-input"
                  :class="{ error: errors.confirmPassword }"
                />
                <button
                  type="button"
                  class="password-toggle"
                  @click="showConfirmPassword = !showConfirmPassword"
                  :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'"
                >
                  {{ showConfirmPassword ? '🙈' : '👁️' }}
                </button>
              </div>
              <span v-if="errors.confirmPassword" class="error-message">{{ errors.confirmPassword }}</span>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number <small>(Optional)</small></label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                placeholder="+977........"
                class="form-input"
                :class="{ error: errors.phone }"
              />
              <span v-if="errors.phone" class="error-message">{{ errors.phone }}</span>
            </div>
            <button type="submit" class="signup-btn" :disabled="loading">
              <span v-if="loading">Creating Account...</span>
              <span v-else>Create Account</span>
            </button>
            <p class="signin-link">
              Already have an account? 
              <router-link to="/login">Login</router-link>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'

const loading = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  phone: '',
  newsletter: true,
  acceptTerms: false
})

const errors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  phone: ''
})

// Password strength calculator
const passwordStrength = computed(() => {
  const password = form.password
  if (!password) return 'weak'
  
  let strength = 0
  if (password.length >= 8) strength++
  if (/[A-Z]/.test(password)) strength++
  if (/[a-z]/.test(password)) strength++
  if (/[0-9]/.test(password)) strength++
  if (/[^A-Za-z0-9]/.test(password)) strength++
  
  if (strength <= 2) return 'weak'
  if (strength <= 4) return 'medium'
  return 'strong'
})

const strengthText = computed(() => {
  switch (passwordStrength.value) {
    case 'weak': return 'Weak password'
    case 'medium': return 'Medium strength'
    case 'strong': return 'Strong password'
    default: return ''
  }
})

// Watch for password changes to clear confirm password error
watch(() => form.password, () => {
  if (errors.confirmPassword) {
    errors.confirmPassword = ''
  }
})

function validateForm() {
  let isValid = true
  
  // Reset errors
  Object.keys(errors).forEach(key => errors[key] = '')

  // Name validation
  if (!form.firstName.trim()) {
    errors.firstName = 'First name is required'
    isValid = false
  }

  if (!form.lastName.trim()) {
    errors.lastName = 'Last name is required'
    isValid = false
  }

  // Email validation
  if (!form.email) {
    errors.email = 'Email is required'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Please enter a valid email address'
    isValid = false
  }

  // Password validation
  if (!form.password) {
    errors.password = 'Password is required'
    isValid = false
  } else if (form.password.length < 8) {
    errors.password = 'Password must be at least 8 characters'
    isValid = false
  } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(form.password)) {
    errors.password = 'Password must include uppercase, lowercase, and numbers'
    isValid = false
  }

  // Confirm password validation
  if (!form.confirmPassword) {
    errors.confirmPassword = 'Please confirm your password'
    isValid = false
  } else if (form.password !== form.confirmPassword) {
    errors.confirmPassword = 'Passwords do not match'
    isValid = false
  }

  // Phone validation (optional but if provided, validate)
  if (form.phone && !/^[\+]?[1-9][\d]{0,15}$/.test(form.phone.replace(/[\s\-\(\)]/g, ''))) {
    errors.phone = 'Please enter a valid phone number'
    isValid = false
  }

  // Terms validation
  if (!form.acceptTerms) {
    alert('Please accept the Terms of Service and Privacy Policy')
    isValid = false
  }

  return isValid
}

async function handleSignUp() {
  if (!validateForm()) return

  loading.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Emit success event to parent
    emit('signup-success', {
      firstName: form.firstName,
      lastName: form.lastName,
      email: form.email,
      phone: form.phone,
      newsletter: form.newsletter
    })
    
    console.log('Sign up successful:', form.email)
    
  } catch (error) {
    console.error('Sign up failed:', error)
    errors.email = 'This email is already registered. Please try another one.'
  } finally {
    loading.value = false
  }
}

function signUpWithGoogle() {
  console.log('Sign up with Google')
  // Implement Google OAuth
}

function signUpWithFacebook() {
  console.log('Sign up with Facebook')
  // Implement Facebook OAuth
}

const emit = defineEmits(['signup-success', 'go-signin', 'go-home', 'show-terms', 'show-privacy'])
</script>

<style scoped>
.signup-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f6f8fb 0%, #f1f5f9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.signup-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  max-width: 1000px;
  width: 100%;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(16, 24, 40, 0.08);
  overflow: hidden;
  min-height: 700px;
}

/* Left Side */
.signup-left {
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
  margin-bottom: 2rem;
}

.benefits {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  text-align: left;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9rem;
}

.benefit-icon {
  font-size: 1.1rem;
}

/* Right Side */
.signup-right {
  padding: 3rem 2.5rem;
  display: flex;
  align-items: center;
}

.signup-form-container {
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
.signup-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.name-fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
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

/* Password Strength */
.password-strength {
  margin-top: 0.5rem;
}

.strength-bar {
  height: 4px;
  border-radius: 2px;
  margin-bottom: 0.25rem;
  transition: all 0.3s ease;
}

.strength-bar.weak {
  width: 33%;
  background: #ef4444;
}

.strength-bar.medium {
  width: 66%;
  background: #f59e0b;
}

.strength-bar.strong {
  width: 100%;
  background: #10b981;
}

.strength-text {
  font-size: 0.75rem;
  color: #6b7280;
}

/* Sign Up Button */
.signup-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 0.875rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 0.5rem;
}

.signup-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.signup-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

/* Sign In Link */
.signin-link {
  text-align: center;
  color: #6b7280;
  margin: 1.5rem 0 0 0;
}

.signin-link a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.25rem;
}

.signin-link a:hover {
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
  .signup-container {
    grid-template-columns: 1fr;
    max-width: 400px;
  }
  
  .signup-left {
    display: none;
  }
  
  .signup-right {
    padding: 2rem 1.5rem;
  }
  
  .name-fields {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .signup-page {
    padding: 1rem;
  }
}
</style>