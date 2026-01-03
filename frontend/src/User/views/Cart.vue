<script setup>
import { useRouter } from 'vue-router'
import { useCart } from '@/composables/useCart'
import { useToast } from '@/composables/useToast'

const router = useRouter()
const { showToast } = useToast()

const {
  cart,
  cartSubtotal,
  shippingCost,
  taxAmount,
  grandTotal,
  isEmpty,
  isPlacing, // loading flag from composable
  incrementQuantity,
  decrementQuantity,
  removeFromCart,
  clearCart,
  placeOrder
} = useCart()

// ✅ Handle async place order with proper error handling and loading state
const handlePlaceOrder = async () => { 
  try { const order = await placeOrder() 
    showToast('Order placed successfully!', 'success') 
    clearCart() 
    router.push('/userdashboard') // redirect after success 
    } catch (err) { 
      console.error('Order error:', err.response?.data || err.message) 
      showToast(err.response?.data?.error || err.message, 'error')
       } 
       }
</script>

<template>
  <section class="cart-container">
    <h1>🛒 Your Cart</h1>

    <div v-if="isEmpty" class="empty-cart">
      <p>Your cart is empty</p>
      <router-link to="/userdashboard" class="btn">Shop Now</router-link>
    </div>

    <div v-else class="cart-grid">
      <!-- ITEMS -->
      <div class="cart-items">
        <div v-for="item in cart" :key="item.id" class="cart-item">
          <img :src="item.image" :alt="item.name" />
          <div class="info">
            <h3>{{ item.name }}</h3>
            <p>${{ item.price }}</p>
            <div class="qty">
              <button @click="decrementQuantity(item.id)" :disabled="isPlacing">−</button>
              <span>{{ item.quantity }}</span>
              <button @click="incrementQuantity(item.id)" :disabled="isPlacing">+</button>
            </div>
          </div>
          <div class="actions">
            <p>${{ (item.price * item.quantity).toFixed(2) }}</p>
            <button class="remove" @click="removeFromCart(item.id)" :disabled="isPlacing">Remove</button>
          </div>
        </div>
      </div>

      <!-- SUMMARY -->
      <aside class="summary">
        <h3>Order Summary</h3>
        <div class="row">
          <span>Subtotal</span>
          <span>${{ cartSubtotal.toFixed(2) }}</span>
        </div>
        <div class="row">
          <span>Shipping</span>
          <span>${{ shippingCost.toFixed(2) }}</span>
        </div>
        <div class="row">
          <span>Tax</span>
          <span>${{ taxAmount.toFixed(2) }}</span>
        </div>
        <hr />
        <div class="row total">
          <span>Total</span>
          <span>${{ grandTotal.toFixed(2) }}</span>
        </div>

        <!-- ✅ Use type="button" to prevent form submit. Disable while placing order -->
        <button
          type="button"
          class="order-btn"
          @click="handlePlaceOrder"
          :disabled="isPlacing"
        >
          {{ isPlacing ? 'Placing Order...' : 'Place Order' }}
        </button>
      </aside>
    </div>
  </section>
</template>

<style scoped>
.cart-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.cart-container h1 {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 2rem;
}

/* EMPTY CART */
.empty-cart {
  text-align: center;
  padding: 4rem 1rem;
}

.empty-cart p {
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
}

.empty-cart .btn {
  background: #3498db;
  color: #fff;
  padding: 0.8rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: background 0.3s;
}

.empty-cart .btn:hover {
  background: #2980b9;
}

/* GRID */
.cart-grid {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 2rem;
}

/* CART ITEMS */
.cart-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: grid;
  grid-template-columns: 100px 1fr auto;
  gap: 1rem;
  padding: 1.25rem;
  background: #fff;
  border-radius: 14px;
  border: 1px solid #eef1f5;
  align-items: center;
  transition: box-shadow 0.2s;
}

.cart-item:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.cart-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
}

/* ITEM INFO */
.info h3 {
  margin-bottom: 0.25rem;
  font-size: 1.1rem;
}

.info p {
  color: #3498db;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

/* QUANTITY */
.qty {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background: #f4f6f8;
  border-radius: 999px;
  padding: 0.25rem 0.75rem;
}

.qty button {
  border: none;
  background: #fff;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  font-size: 1.1rem;
  cursor: pointer;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  transition: background 0.2s;
}

.qty button:hover {
  background: #ecf0f1;
}

.qty span {
  min-width: 24px;
  text-align: center;
  font-weight: 600;
}

/* ACTIONS */
.actions {
  text-align: right;
}

.actions p {
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.remove {
  background: none;
  border: none;
  color: #e74c3c;
  cursor: pointer;
  font-size: 0.9rem;
  transition: color 0.2s;
}

.remove:hover {
  color: #c0392b;
}

/* SUMMARY */
.summary {
  position: sticky;
  top: 2rem;
  background: #fff;
  padding: 1.5rem;
  border-radius: 14px;
  border: 1px solid #eef1f5;
}

.summary h3 {
  margin-bottom: 1.5rem;
}

.summary .row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  font-size: 0.95rem;
}

.summary hr {
  border: none;
  border-top: 1px solid #eef1f5;
  margin: 1.25rem 0;
}

.summary .total {
  font-size: 1.1rem;
  font-weight: 700;
}

/* ORDER BUTTON */
.order-btn {
  width: 100%;
  margin-top: 1.25rem;
  padding: 1rem;
  background: #2ecc71;
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
}

.order-btn[disabled] {
  opacity: 0.7;
  cursor: not-allowed;
}

.order-btn:hover:not([disabled]) {
  background: #27ae60;
}

/* RESPONSIVE */
@media (max-width: 992px) {
  .cart-grid {
    grid-template-columns: 1fr;
  }

  .summary {
    position: static;
  }
}

@media (max-width: 576px) {
  .cart-item {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .cart-item img {
    margin: 0 auto;
  }

  .actions {
    text-align: center;
  }
}
</style>