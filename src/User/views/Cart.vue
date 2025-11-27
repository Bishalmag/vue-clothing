<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCart } from '@/composables/useCart'
import { useToast } from '@/composables/useToast'

const router = useRouter()
const { showToast } = useToast()
const {
  cart,
  cartSubtotal: subtotal,
  shippingCost,
  taxAmount: tax,
  grandTotal: total,
  incrementQuantity,
  decrementQuantity,
  removeFromCart
} = useCart()

const isCartEmpty = computed(() => cart.value.length === 0)

const increaseQuantity = (itemId) => {
  incrementQuantity(itemId)
}

const decreaseQuantity = (itemId) => {
  decrementQuantity(itemId)
}

const removeItem = (itemId) => {
  const item = cart.value.find(item => item.id === itemId)
  if (item) {
    removeFromCart(itemId)
    showToast(`"${item.name}" removed from cart`, 'success')
  }
}

const proceedToCheckout = () => {
  if (isCartEmpty.value) {
    showToast('Your cart is empty', 'error')
    return
  }
  router.push('/checkout')
}
</script>

<template>
  <div class="cart-page">
    <div class="cart-header">
      <h1>Shopping Cart</h1>
      <p>{{ isCartEmpty ? 'Your cart is empty' : `${cart.length} items in your cart` }}</p>
    </div>

    <div class="cart-content">
      <div v-if="isCartEmpty" class="empty-cart">
        <div class="empty-icon">🛒</div>
        <h2>Your cart is empty</h2>
        <router-link to="/userdashboard" class="continue-shopping-btn">
          Continue Shopping
        </router-link>
      </div>

      <div v-else class="cart-layout">
        <div class="cart-items">
          <div v-for="item in cart" :key="item.id" class="cart-item">
            <img :src="item.image" :alt="item.name" class="item-image" />
            
            <div class="item-info">
              <h3>{{ item.name }}</h3>
              <p class="category">{{ item.category }}</p>
              <p class="price">${{ item.price }}</p>
            </div>

            <div class="item-controls">
              <div class="quantity-controls">
                <button 
                  @click="decreaseQuantity(item.id)" 
                  :disabled="item.quantity <= 1"
                > - </button>
                <span>{{ item.quantity }}</span>
                <button @click="increaseQuantity(item.id)"> + </button>
              </div>
              <button @click="removeItem(item.id)" class="remove-btn">Remove</button>
            </div>

            <div class="item-total">${{ (item.price * item.quantity).toFixed(2) }}</div>
          </div>
        </div>

        <div class="cart-summary">
          <div class="summary-card">
            <h3>Order Summary</h3>
            
            <div class="summary-row">
              <span>Subtotal</span>
              <span>${{ subtotal.toFixed(2) }}</span>
            </div>
            
            <div class="summary-row">
              <span>Shipping</span>
              <span>${{ shippingCost.toFixed(2) }}</span>
            </div>
            
            <div class="summary-row">
              <span>Tax</span>
              <span>${{ tax.toFixed(2) }}</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="summary-row total">
              <span>Total</span>
              <span>${{ total.toFixed(2) }}</span>
            </div>

            <button class="checkout-btn" @click="proceedToCheckout">Proceed to Checkout</button>
            <router-link to="/userdashboard" class="continue-link">Continue Shopping</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cart-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.cart-header {
  text-align: center;
  margin-bottom: 2rem;
}

.cart-header h1 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.empty-cart {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.continue-shopping-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: grid;
  grid-template-columns: 80px 1fr auto auto;
  gap: 1rem;
  padding: 1.5rem;
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  align-items: center;
}

.item-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}

.item-info h3 {
  margin-bottom: 0.25rem;
}

.category {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.price {
  color: #3498db;
  font-weight: 600;
}

.item-controls {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #f8f9fa;
  border-radius: 8px;
  padding: 0.25rem;
}

.quantity-controls button {
  width: 32px;
  height: 32px;
  border: none;
  background: white;
  border-radius: 6px;
  cursor: pointer;
}

.quantity-controls button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.remove-btn {
  background: none;
  border: none;
  color: #e74c3c;
  cursor: pointer;
  text-decoration: underline;
}

.item-total {
  font-weight: 600;
}

.cart-summary {
  position: sticky;
  top: 2rem;
}

.summary-card {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid #eef2f6;
}

.summary-card h3 {
  margin-bottom: 1.5rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.summary-row.total {
  font-weight: 600;
  font-size: 1.1rem;
}

.divider {
  height: 1px;
  background: #eef2f6;
  margin: 1rem 0;
}

.checkout-btn {
  width: 100%;
  background: #2ecc71;
  color: white;
  border: none;
  padding: 1rem;
  border-radius: 8px;
  cursor: pointer;
  margin-bottom: 1rem;
}

.continue-link {
  display: block;
  text-align: center;
  color: #3498db;
  text-decoration: none;
}

@media (max-width: 968px) {
  .cart-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .cart-page {
    padding: 1rem;
  }
  
  .cart-item {
    grid-template-columns: 80px 1fr;
  }
  
  .item-controls {
    grid-column: 1 / -1;
    flex-direction: row;
    justify-content: space-between;
  }
  
  .item-total {
    grid-column: 2;
  }
}
</style>