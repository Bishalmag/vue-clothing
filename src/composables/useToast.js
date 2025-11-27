export function useToast() {
  const showToast = (message, type = 'info', duration = 3000) => {
    if (window.showToast) {
      window.showToast(message, type, duration)
    } else {
      console.log('Toast:', message)
    }
  }
  
  // Add these convenience methods
  const success = (message, duration = 3000) => showToast(message, 'success', duration)
  const error = (message, duration = 3000) => showToast(message, 'error', duration)
  const info = (message, duration = 3000) => showToast(message, 'info', duration)
  
  return { 
    showToast,
    success,
    error,
    info
  }
}