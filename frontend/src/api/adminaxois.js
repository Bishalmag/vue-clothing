import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api/admin',
  headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
})

api.interceptors.request.use(config => {
  // normalize url (handles absolute urls too)
  const urlPath = (config.url || '').toString()

  // If request is for admin endpoints, use adminToken, else use user token
  const useAdmin = urlPath.startsWith('/admin') || urlPath.includes('/api/admin') || config.baseURL?.includes('/admin')
  const token = useAdmin ? localStorage.getItem('adminToken') : localStorage.getItem('auth_token')

  if (token) {
    config.headers = config.headers || {}
    config.headers['Authorization'] = `Bearer ${token}`
  } else if (config.headers && config.headers['Authorization']) {
    delete config.headers['Authorization']
  }

  // optional debug
  // console.debug('[axios] req', config.method, config.url, 'admin?', useAdmin, 'hasAuth:', !!token)
  return config
}, err => Promise.reject(err))

api.interceptors.response.use(
  res => res,
  err => {
    const status = err.response?.status
    const reqUrl = err.config?.url || ''
    if (status === 401) {
      // decide which token to clear depending on endpoint
      const isAdminReq = reqUrl.startsWith('/admin') || reqUrl.includes('/admin')
      if (isAdminReq && localStorage.getItem('adminToken')) {
        localStorage.removeItem('adminToken')
      } else if (!isAdminReq && localStorage.getItem('auth_token')) {
        localStorage.removeItem('auth_token')
      }
      // Optionally redirect depending on where user is
      if (!window.location.pathname.includes('/login') && !window.location.pathname.includes('/adminlogin')) {
        // choose to redirect user/admin appropriately (you may want separate admin login route)
        if (isAdminReq) window.location.href = '/adminlogin'
        else window.location.href = '/login'
      }
    }
    return Promise.reject(err)
  }
)

export default api