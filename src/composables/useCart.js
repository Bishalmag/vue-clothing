import { ref, computed } from 'vue'
import { useLocalStorage } from './useLocalStorage'

export function useCart() {
  const cart = useLocalStorage('cart', [])

  const cartSubtotal = computed(() => 
    cart.value.reduce((total, item) => total + (item.price * item.quantity), 0)
  )

  const shippingCost = computed(() => 
    cartSubtotal.value > 100 ? 0 : 9.99
  )

  const taxAmount = computed(() => 
    cartSubtotal.value * 0.08
  )

  const grandTotal = computed(() => 
    cartSubtotal.value + shippingCost.value + taxAmount.value
  )

  const incrementQuantity = (itemId) => {
    const item = cart.value.find(item => item.id === itemId)
    if (item) item.quantity++
  }

  const decrementQuantity = (itemId) => {
    const item = cart.value.find(item => item.id === itemId)
    if (item && item.quantity > 1) item.quantity--
  }

  const removeFromCart = (itemId) => {
    cart.value = cart.value.filter(item => item.id !== itemId)
  }

  const addToCart = (product) => {
    const existingItem = cart.value.find(item => item.id === product.id)
    if (existingItem) {
      existingItem.quantity++
    } else {
      cart.value.push({ ...product, quantity: 1 })
    }
  }

  return {
    cart,
    cartSubtotal,
    shippingCost,
    taxAmount,
    grandTotal,
    incrementQuantity,
    decrementQuantity,
    removeFromCart,
    addToCart
  }
}