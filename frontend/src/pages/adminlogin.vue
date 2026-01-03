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
            <div class="password-input">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Password"
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

          <button type="submit" class="login-btn" :disabled="loading">
            <span v-if="loading">Signing In...</span>
            <span v-else>Sign In</span>
          </button>
        </form>

        <p class="back-link">
          <router-link to="/">← Back to Home</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import adminApi from '@/api/adminaxois';
import { useLocalStorage } from '@/composables/useLocalStorage';

const router = useRouter();
const loading = ref(false);
const showPassword = ref(false);

const form = reactive({
  email: '',
  password: ''
});

const errors = reactive({
  email: '',
  password: ''
});

const adminToken = useLocalStorage('adminToken', null);

function validateForm() {
  let isValid = true;
  errors.email = '';
  errors.password = '';

  if (!form.email) {
    errors.email = 'Email is required';
    isValid = false;
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Please enter a valid email address';
    isValid = false;
  }

  if (!form.password) {
    errors.password = 'Password is required';
    isValid = false;
  } else if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters';
    isValid = false;
  }

  return isValid;
}

async function handleAdminLogin() {
  if (!validateForm()) return;
  loading.value = true;

  try {
    const response = await adminApi.post('/login', {
      email: form.email,
      password: form.password
    });

    const token = response.data?.token;

    if (!token) {
      errors.email = 'Login failed. Token not received.';
      return;
    }

    adminToken.value = token;

    localStorage.setItem('adminUser', JSON.stringify({
      id: response.data.id,
      username: response.data.username,
      email: response.data.email
    }));

    adminApi.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    router.push('/admindashboard');

  } catch (error) {
    if (error.response && error.response.status === 401) {
      errors.email = 'Invalid admin credentials. Please try again.';
    } else {
      console.error('Admin login error:', error.response?.data || error.message);
      errors.email = 'Login failed. Please try again.';
    }
  } finally {
    loading.value = false;
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

.password-input {
  position: relative;
  width: 84%;
}

.password-input .form-input {
  width: 100%;
  padding-right: 50px;
}

.password-toggle {
  position: absolute;
  left: 105%;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 5px;
  font-size: 1.2rem;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
}

.password-toggle:hover {
  color: #374151;
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