// src/api/axios.js
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Attach latest token per request (avoids race)
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers = config.headers || {}
      config.headers['Authorization'] = `Bearer ${token}`
    } else if (config.headers && config.headers['Authorization']) {
      delete config.headers['Authorization']
    }
    // console.debug(`[axios] ${config.method?.toUpperCase()} ${config.url} hasAuth=${!!token}`)
    return config
  },
  error => Promise.reject(error)
)

// Response interceptor: only clear token if a token was present
api.interceptors.response.use(
  response => response,
  error => {
    const status = error.response?.status
    const reqUrl = error.config?.url || ''
    if (status === 401) {
      const hadToken = !!localStorage.getItem('auth_token')
      console.warn(`[axios] 401 from ${reqUrl}; hadToken=${hadToken}`)
      if (hadToken) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user')
        delete api.defaults.headers.common['Authorization']
        if (!window.location.pathname.includes('/login')) {
          window.location.href = '/login'
        }
      }
    }
    return Promise.reject(error)
  }
)

export default api