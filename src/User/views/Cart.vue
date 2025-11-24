<template>
  <div class="cart-page">
    <div class="cart-header">
      <h1>Shopping Cart</h1>
      <p v-if="cartItems.length > 0">{{ cartItems.length }} items in your cart</p>
      <p v-else>Your cart is empty</p>
    </div>

    <div class="cart-content">
      <!-- Empty State -->
      <div v-if="cartItems.length === 0" class="empty-cart">
        <div class="empty-icon">🛒</div>
        <h2>Your cart is empty</h2>
        <p>Add some items to get started</p>
        <router-link to="/" class="continue-shopping-btn">
          Continue Shopping
        </router-link>
      </div>

      <!-- Cart Items -->
      <div v-else class="cart-items-section">
        <div class="cart-items">
          <div v-for="item in cartItems" :key="item.id" class="cart-item">
            <div class="item-image">
              <img :src="item.image" :alt="item.name" />
            </div>
            
            <div class="item-details">
              <h3 class="item-name">{{ item.name }}</h3>
              <p class="item-category">{{ item.category }}</p>
              <p class="item-price">${{ item.price }}</p>
            </div>

            <div class="item-controls">
              <div class="quantity-controls">
                <button 
                  @click="decreaseQuantity(item.id)" 
                  class="quantity-btn"
                  :disabled="item.quantity <= 1"
                >
                  -
                </button>
                <span class="quantity">{{ item.quantity }}</span>
                <button 
                  @click="increaseQuantity(item.id)" 
                  class="quantity-btn"
                >
                  +
                </button>
              </div>
              
              <button 
                @click="removeItem(item.id)" 
                class="remove-btn"
              >
                Remove
              </button>
            </div>

            <div class="item-total">
              ${{ (item.price * item.quantity).toFixed(2) }}
            </div>
          </div>
        </div>

        <!-- Cart Summary -->
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
            
            <div class="summary-divider"></div>
            
            <div class="summary-row total">
              <span>Total</span>
              <span>${{ total.toFixed(2) }}</span>
            </div>

            <button class="checkout-btn" @click="proceedToCheckout">
              Proceed to Checkout
            </button>
            
            <router-link to="/" class="continue-shopping-link">
              Continue Shopping
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
// import { useToast } from '@/composables/useToast'

const router = useRouter()

// Sample cart data - replace with your actual data source
const cartItems = ref([
  {
    id: 1,
    name: 'Classic White T-Shirt',
    category: 'T-Shirts',
    price: 29.99,
    quantity: 2,
    image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=150'
  },
  {
    id: 2,
    name: 'Slim Fit Jeans',
    category: 'Jeans',
    price: 79.99,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=150'
  },
  {
    id: 3,
    name: 'Sports Sneakers',
    category: 'Shoes',
    price: 129.99,
    quantity: 1,
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=150'
  }
])

// Computed values
const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const shippingCost = computed(() => {
  return subtotal.value > 100 ? 0 : 9.99
})

const tax = computed(() => {
  return subtotal.value * 0.08 // 8% tax
})

const total = computed(() => {
  return subtotal.value + shippingCost.value + tax.value
})

// Methods
const increaseQuantity = (itemId) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (item) {
    item.quantity++
  }
}

const decreaseQuantity = (itemId) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (item && item.quantity > 1) {
    item.quantity--
  }
}

const removeItem = (itemId) => {
  const itemIndex = cartItems.value.findIndex(item => item.id === itemId)
  
  if (itemIndex !== -1) {
    const itemName = cartItems.value[itemIndex].name
    cartItems.value.splice(itemIndex, 1)
    showToast(`"${itemName}" removed from cart`, 'success')
  }
}

const proceedToCheckout = () => {
  // Navigate to checkout page
  router.push('/checkout')
}
</script>

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
  color: #344767;
  margin-bottom: 0.5rem;
}

.cart-header p {
  color: #6c757d;
  font-size: 1rem;
}

/* Empty State */
.empty-cart {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-cart h2 {
  color: #344767;
  margin-bottom: 0.5rem;
}

.empty-cart p {
  color: #6c757d;
  margin-bottom: 2rem;
}

.continue-shopping-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.2s;
}

.continue-shopping-btn:hover {
  background: #2980b9;
}

/* Cart Items Section */
.cart-items-section {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
  align-items: start;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: grid;
  grid-template-columns: 100px 1fr auto auto;
  gap: 1rem;
  padding: 1.5rem;
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  align-items: center;
}

.item-image img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}

.item-details h3 {
  color: #344767;
  margin-bottom: 0.25rem;
  font-size: 1.1rem;
}

.item-category {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.item-price {
  color: #3498db;
  font-weight: 600;
  font-size: 1.1rem;
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

.quantity-btn {
  width: 32px;
  height: 32px;
  border: none;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s;
}

.quantity-btn:hover:not(:disabled) {
  background: #e9ecef;
}

.quantity-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.quantity {
  min-width: 40px;
  text-align: center;
  font-weight: 500;
}

.remove-btn {
  background: none;
  border: none;
  color: #e74c3c;
  cursor: pointer;
  font-size: 0.9rem;
  text-decoration: underline;
}

.remove-btn:hover {
  color: #c0392b;
}

.item-total {
  font-size: 1.2rem;
  font-weight: 600;
  color: #344767;
  min-width: 80px;
  text-align: right;
}

/* Cart Summary */
.cart-summary {
  position: sticky;
  top: 2rem;
}

.summary-card {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.summary-card h3 {
  color: #344767;
  margin-bottom: 1.5rem;
  font-size: 1.2rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  color: #6c757d;
}

.summary-row.total {
  font-size: 1.2rem;
  font-weight: 600;
  color: #344767;
}

.summary-divider {
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
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  margin-bottom: 1rem;
  transition: background-color 0.2s;
}

.checkout-btn:hover {
  background: #27ae60;
}

.continue-shopping-link {
  display: block;
  text-align: center;
  color: #3498db;
  text-decoration: none;
  font-weight: 500;
}

.continue-shopping-link:hover {
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 968px) {
  .cart-items-section {
    grid-template-columns: 1fr;
  }
  
  .cart-summary {
    position: static;
  }
}

@media (max-width: 640px) {
  .cart-page {
    padding: 1rem;
  }
  
  .cart-item {
    grid-template-columns: 80px 1fr;
    gap: 1rem;
  }
  
  .item-controls {
    grid-column: 1 / -1;
    flex-direction: row;
    justify-content: space-between;
  }
  
  .item-total {
    grid-column: 2;
    text-align: left;
  }
}
</style>