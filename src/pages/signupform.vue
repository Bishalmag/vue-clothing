<template>
  <div class="signup-page">
    <div class="signup-card">
      
      <div class="brand-header" @click="$emit('go-home')">
        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
        </svg>
        <h1>Clothing Store<span class="dot">.</span></h1>
      </div>

      <div class="form-header">
        <h2>Create Account</h2>
        <p>Join our community and discover your perfect style.</p>
      </div>

      <form @submit.prevent="handleSignUp" class="signup-form">
        <div class="name-fields">
          <div class="form-group">
            <label for="firstName">First Name</label>
            <input
              id="firstName"
              v-model="form.firstName"
              type="text"
              placeholder="First Name"
              required
              class="form-input"
              :class="{ 'input-error': errors.firstName }"
            />
            <span v-if="errors.firstName" class="error-message">{{ errors.firstName }}</span>
          </div>

          <div class="form-group">
            <label for="lastName">Last Name</label>
            <input
              id="lastName"
              v-model="form.lastName"
              type="text"
              placeholder="Last Name"
              required
              class="form-input"
              :class="{ 'input-error': errors.lastName }"
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
              placeholder="Create a strong password"
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
          <div class="password-feedback">
            <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
            <div v-else class="password-strength-indicator" :data-strength="passwordStrength">
              <span class="strength-bar"></span>
              <span class="strength-text">{{ strengthText }}</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <div class="password-input">
            <input
              id="confirmPassword"
              v-model="form.confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="Confirm password"
              required
              class="form-input"
              :class="{ 'input-error': errors.confirmPassword }"
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
        
        <div class="terms-group">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.acceptTerms" required />
            I agree to the <a href="#" @click.prevent="$emit('show-terms')">Terms of Service</a> and <a href="#" @click.prevent="$emit('show-privacy')">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="signup-btn" :disabled="loading">
          <span v-if="loading">Creating Account...</span>
          <span v-else>Create Account</span>
        </button>
      </form>

      <p class="signin-link">
        Already have an account? 
        <router-link to="/login">Login</router-link>
      </p>
      
      <router-link to="/" class="back-home-link">← Back to Home</router-link>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, defineEmits } from 'vue'
import { useRouter } from 'vue-router'

const loading = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const router = useRouter()
const emit = defineEmits(['signup-success', 'go-signin', 'go-home', 'show-terms', 'show-privacy'])


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

const passwordStrength = computed(() => {
  const password = form.password
  if (!password) return 'none'
  
  let strength = 0
  if (password.length >= 8) strength++
  if (/[A-Z]/.test(password)) strength++
  if (/[a-z]/.test(password)) strength++
  if (/[0-9]/.test(password)) strength++
  if (/[^A-Za-z0-9]/.test(password)) strength++
  
  if (strength === 0) return 'none'
  if (strength <= 2) return 'weak'
  if (strength <= 4) return 'medium'
  return 'strong'
})

const strengthText = computed(() => {
  switch (passwordStrength.value) {
    case 'none': return 'Enter password'
    case 'weak': return 'Weak'
    case 'medium': return 'Medium'
    case 'strong': return 'Strong'
    default: return ''
  }
})

watch(() => form.password, () => {
  if (errors.confirmPassword) {
    errors.confirmPassword = ''
  }
})

function validateForm() {
  let isValid = true
  
  Object.keys(errors).forEach(key => errors[key] = '')

  if (!form.firstName.trim()) {
    errors.firstName = 'First name is required'
    isValid = false
  }

  if (!form.lastName.trim()) {
    errors.lastName = 'Last name is required'
    isValid = false
  }

  if (!form.email) {
    errors.email = 'Email is required'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Enter a valid email address'
    isValid = false
  }

  if (!form.password) {
    errors.password = 'Password is required'
    isValid = false
  } else if (form.password.length < 8) {
    errors.password = 'Min 8 characters'
    isValid = false
  } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(form.password)) {
    errors.password = 'Needs uppercase, lowercase, and numbers'
    isValid = false
  }

  if (!form.confirmPassword) {
    errors.confirmPassword = 'Confirm password'
    isValid = false
  } else if (form.password !== form.confirmPassword) {
    errors.confirmPassword = 'Passwords do not match'
    isValid = false
  }

  if (!form.acceptTerms) {
    alert('You must accept the Terms and Policy.')
    isValid = false
  }

  return isValid
}

async function handleSignUp() {
  if (!validateForm()) return

  loading.value = true
  
  try {
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    emit('signup-success', {
      firstName: form.firstName,
      lastName: form.lastName,
      email: form.email,
    })
    
    // router.push('/userdashboard') 
    
  } catch (error) {
    console.error('Sign up failed:', error)
    errors.email = 'This email is already registered.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.signup-page {
  min-height: 100vh;
  background: #f0f4f8;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.signup-card {
  max-width: 480px;
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

.signup-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.name-fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.terms-group {
  margin-top: 0.5rem;
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

.password-feedback {
  min-height: 20px;
}

.password-strength-indicator {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-top: 0.25rem;
}

.strength-bar {
  height: 5px;
  border-radius: 3px;
  width: 100px;
  transition: all 0.3s ease;
  background: #e5e7eb;
}

.password-strength-indicator[data-strength="weak"] .strength-bar {
  width: 33%;
  background: #ef4444;
}

.password-strength-indicator[data-strength="medium"] .strength-bar {
  width: 66%;
  background: #f59e0b;
}

.password-strength-indicator[data-strength="strong"] .strength-bar {
  width: 100%;
  background: #10b981;
}

.password-strength-indicator[data-strength="none"] .strength-bar {
  width: 0;
}

.strength-text {
  font-size: 0.75rem;
  color: #64748b;
  min-width: 60px;
}

.checkbox-label {
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  color: #64748b;
  cursor: pointer;
  line-height: 1.4;
}

.checkbox-label input {
  margin-top: 3px;
}

.checkbox-label a {
  color: #4f46e5;
  text-decoration: none;
  font-weight: 500;
}

.checkbox-label a:hover {
  text-decoration: underline;
}

.signup-btn {
  background: #4f46e5;
  color: white;
  border: none;
  padding: 0.875rem 1.5rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 1rem;
}

.signup-btn:hover:not(:disabled) {
  background: #4338ca;
}

.signup-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.signin-link {
  text-align: center;
  font-size: 0.9rem;
  color: #64748b;
}

.signin-link a {
  color: #4f46e5;
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.25rem;
}

.signin-link a:hover {
  text-decoration: underline;
}

.back-home-link {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
  text-align: center;
  text-decoration: none;
  color: #64748b;
  font-size: 0.9rem;
  font-weight: 500;
}

.back-home-link:hover {
  color: #334155;
}

@media (max-width: 480px) {
  .signup-card {
    padding: 1.5rem;
  }
  .name-fields {
    grid-template-columns: 1fr;
  }
}
</style>