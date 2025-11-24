<template>
  <div class="orders-page">
    <div class="orders-header">
      <h1>My Orders</h1>
      <p>{{ orders.length }} orders found</p>
    </div>

    <div class="orders-content">
      <!-- Empty State -->
      <div v-if="orders.length === 0" class="empty-orders">
        <div class="empty-icon">📦</div>
        <h2>No orders yet</h2>
        <p>Your orders will appear here</p>
        <router-link to="/" class="shop-now-btn">
          Start Shopping
        </router-link>
      </div>

      <!-- Orders List -->
      <div v-else class="orders-list">
        <div v-for="order in orders" :key="order.id" class="order-card">
          <div class="order-header">
            <div class="order-info">
              <h3>Order #{{ order.id }}</h3>
              <p class="order-date">{{ order.date }}</p>
            </div>
            <div class="order-status" :class="order.status">
              {{ order.status }}
            </div>
          </div>

          <div class="order-items">
            <div v-for="item in order.items" :key="item.id" class="order-item">
              <img :src="item.image" :alt="item.name" class="item-image" />
              <div class="item-details">
                <h4>{{ item.name }}</h4>
                <p>Qty: {{ item.quantity }}</p>
              </div>
              <div class="item-price">${{ item.price }}</div>
            </div>
          </div>

          <div class="order-footer">
            <div class="order-total">
              Total: ${{ order.total }}
            </div>
            <div class="order-actions">
              <button class="track-btn" @click="trackOrder(order.id)">
                Track Order
              </button>
              <button class="details-btn" @click="viewOrderDetails(order.id)">
                View Details
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Sample orders data
const orders = ref([
  {
    id: 'ORD-001',
    date: 'Nov 19, 2024',
    status: 'delivered',
    total: 159.98,
    items: [
      {
        id: 1,
        name: 'Classic White T-Shirt',
        quantity: 2,
        price: 29.99,
        image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100'
      }
    ]
  },
  {
    id: 'ORD-002',
    date: 'Nov 15, 2024',
    status: 'shipped',
    total: 129.99,
    items: [
      {
        id: 2,
        name: 'Sports Sneakers',
        quantity: 1,
        price: 129.99,
        image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100'
      }
    ]
  },
  {
    id: 'ORD-003',
    date: 'Nov 10, 2024',
    status: 'processing',
    total: 79.99,
    items: [
      {
        id: 3,
        name: 'Slim Fit Jeans',
        quantity: 1,
        price: 79.99,
        image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=100'
      }
    ]
  }
])

// Methods
const trackOrder = (orderId) => {
  console.log('Tracking order:', orderId)
  
}

const viewOrderDetails = (orderId) => {
  router.push(`/order/${orderId}`)
}
</script>

<style scoped>
.orders-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.orders-header {
  text-align: center;
  margin-bottom: 2rem;
}

.orders-header h1 {
  font-size: 2rem;
  color: #344767;
  margin-bottom: 0.5rem;
}

.orders-header p {
  color: #6c757d;
}

/* Empty State */
.empty-orders {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-orders h2 {
  color: #344767;
  margin-bottom: 0.5rem;
}

.empty-orders p {
  color: #6c757d;
  margin-bottom: 2rem;
}

.shop-now-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
}

.shop-now-btn:hover {
  background: #2980b9;
}

/* Orders List */
.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.order-card {
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  padding: 1.5rem;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 1rem;
}

.order-info h3 {
  color: #344767;
  margin-bottom: 0.25rem;
}

.order-date {
  color: #6c757d;
  font-size: 0.9rem;
}

.order-status {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
  text-transform: capitalize;
}

.order-status.delivered {
  background: #d4edda;
  color: #155724;
}

.order-status.shipped {
  background: #cce7ff;
  color: #004085;
}

.order-status.processing {
  background: #fff3cd;
  color: #856404;
}

/* Order Items */
.order-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1rem;
}

.order-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 6px;
}

.item-details {
  flex: 1;
}

.item-details h4 {
  color: #344767;
  margin-bottom: 0.25rem;
}

.item-details p {
  color: #6c757d;
  font-size: 0.9rem;
}

.item-price {
  font-weight: 600;
  color: #3498db;
}

/* Order Footer */
.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid #eef2f6;
}

.order-total {
  font-size: 1.1rem;
  font-weight: 600;
  color: #344767;
}

.order-actions {
  display: flex;
  gap: 0.5rem;
}

.track-btn, .details-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  font-size: 0.9rem;
}

.track-btn {
  background: #3498db;
  color: white;
}

.track-btn:hover {
  background: #2980b9;
}

.details-btn {
  background: #6c757d;
  color: white;
}

.details-btn:hover {
  background: #5a6268;
}

/* Responsive */
@media (max-width: 768px) {
  .orders-page {
    padding: 1rem;
  }
  
  .order-header {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .order-footer {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
  
  .order-actions {
    justify-content: center;
  }
}
</style>