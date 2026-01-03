import { ref } from 'vue'
import api from '../api/axios'

// Create reactive state (outside function for shared state)
const toasts = ref([])
const user = ref(null)
let hasShownWelcome = false

// Toast function
const showToast = (message, type = 'info', duration = 3000) => {
  const id = Date.now() + Math.random()
  toasts.value.push({ id, message, type })
  
  setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, duration)
}

// Fetch current user
const fetchUser = async () => {
  try {
    const response = await api.get('/me')
    user.value = response.data.user ?? response.data
    
    if (!hasShownWelcome && user.value) {
      const name = user.value?.first_name
        ? `${user.value.first_name} ${user.value.last_name ?? ''}`.trim()
        : (user.value?.name ?? user.value?.username ?? 'User')
      
      showToast(`Welcome, ${name}!`, 'success')
      hasShownWelcome = true
    }
  } catch (error) {
    console.error('fetchUser error:', error)
  }
}

// Logout
const logout = async () => {
  try {
    await api.post('/logout')
  } catch (e) {
    console.error('logout request error:', e)
  } finally {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    user.value = null
    hasShownWelcome = false
    showToast('Logged out successfully', 'success')
    window.location.href = '/login'
  }
}

// Simple composable
export function useToast() {
  return {
    toasts, // Already reactive
    user, // Already reactive
    showToast,
    fetchUser,
    logout
  }
}

// Named exports for direct import
export { toasts, user, showToast, fetchUser, logout }