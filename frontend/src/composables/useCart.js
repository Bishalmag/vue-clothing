import { computed, ref } from 'vue'
import { useLocalStorage } from './useLocalStorage'
import api from '@/api/axios'

export function useCart() { // ✅ named export
  const cart = useLocalStorage('cart', [])

  const cartCount = computed(() =>
    cart.value.reduce((sum, item) => sum + item.quantity, 0)
  )

  const cartSubtotal = computed(() =>
    cart.value.reduce((total, item) => total + item.price * item.quantity, 0)
  )

  const shippingCost = computed(() =>
    cartSubtotal.value > 100 ? 0 : cart.value.length ? 9.99 : 0
  )

  const taxAmount = computed(() => cartSubtotal.value * 0.08)

  const grandTotal = computed(() =>
    cartSubtotal.value + shippingCost.value + taxAmount.value
  )

  const isEmpty = computed(() => cart.value.length === 0)

  const isPlacing = ref(false) // loading flag to prevent duplicate orders

  const incrementQuantity = (id) => {
    const item = cart.value.find(i => i.id === id)
    if (item) item.quantity++
  }

  const decrementQuantity = (id) => {
    const item = cart.value.find(i => i.id === id)
    if (item && item.quantity > 1) item.quantity--
  }

  const removeFromCart = (id) => {
    cart.value = cart.value.filter(i => i.id !== id)
  }

  const clearCart = () => {
    cart.value = []
  }

  const addToCart = (product) => {
    const item = cart.value.find(i => i.id === product.id)
    if (item) {
      item.quantity++
    } else {
      cart.value.push({ ...product, quantity: 1 })
    }
  }

  const placeOrder = async () => {
    const token = localStorage.getItem('auth_token')
    if (!token) throw new Error('Please login to place order')
    if (cart.value.length === 0) throw new Error('Cart is empty')

    const orderData = {
      total_amount: grandTotal.value,
      status: 0,
      items: cart.value.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        total_price: item.price * item.quantity
      }))
    }

    isPlacing.value = true
    try {
      // include token if present; if your axios instance already adds auth headers this is harmless
      const headers = token ? { Authorization: `Bearer ${token}` } : {}
      const response = await api.post('/orders', orderData, { headers })
      isPlacing.value = false
      return response.data
    } catch (error) {
      isPlacing.value = false
      // normalize error message so components can reliably use err.message
      const message =
        error.response?.data?.error ||
        error.response?.data?.message ||
        error.message ||
        'Failed to place order'
      throw new Error(message)
    }
  }

  return {
    cart,
    cartCount,
    cartSubtotal,
    shippingCost,
    taxAmount,
    grandTotal,
    isEmpty,
    isPlacing,
    incrementQuantity,
    decrementQuantity,
    removeFromCart,
    clearCart,
    addToCart,
    placeOrder
  }
}