<template>
  <div class="my-order">
    <h1>My Orders</h1>
    
    <div v-if="loading" class="loading">Loading orders...</div>
    
    <div v-if="!loading && orders.length === 0" class="empty">
      <p>No orders yet</p>
      <router-link to="/userdashboard" class="btn">Start Shopping</router-link>
    </div>
    
    <div v-else class="orders-list">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-header">
          <div>
            <h3>Order #{{ order.id }}</h3>
            <p class="date">{{ formatDate(order.created_at) }}</p>
          </div>
          <div class="status" :class="getStatusClass(order.status)">
            {{ getStatusText(order.status) }}
          </div>
        </div>
        
        <div class="order-items">
          <div v-for="item in order.items" :key="item.order_item_id" class="order-item">
            <img :src="item.product?.image || '/placeholder.jpg'" alt="">
            <div class="item-info">
              <h4>{{ item.product?.name || 'Product' }}</h4>
              <p>Quantity: {{ item.quantity }} × ${{ item.product?.price || '0.00' }}</p>
            </div>
            <div class="item-total">${{ item.total_price }}</div>
          </div>
        </div>
        
        <div class="order-footer">
          <div class="order-total">
            Total: <strong>${{ order.total_amount }}</strong>
          </div>
          <button class="view-details" @click="viewOrderDetails(order.id)">
            View Details
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCart } from '@/composables/useCart'
import { useToast } from '@/composables/useToast'

const { getMyOrders } = useCart()
const { showToast } = useToast()

const orders = ref([])
const loading = ref(true)

const fetchOrders = async () => {
  try {
    loading.value = true
    const data = await getMyOrders()
    orders.value = data.data || data
  } catch (error) {
    showToast(error.message || 'Failed to load orders', 'error')
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusText = (status) => {
  const statusMap = {
    0: 'Pending',
    1: 'Processing',
    2: 'Shipped',
    3: 'Delivered',
    4: 'Cancelled'
  }
  return statusMap[status] || 'Unknown'
}

const getStatusClass = (status) => {
  const classMap = {
    0: 'pending',
    1: 'processing',
    2: 'shipped',
    3: 'delivered',
    4: 'cancelled'
  }
  return classMap[status] || 'pending'
}

const viewOrderDetails = (orderId) => {
  // Navigate to order details page or show modal
  console.log('View order details:', orderId)
}

onMounted(fetchOrders)
</script>

<style scoped>
.my-order {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.my-order h1 {
  text-align: center;
  margin-bottom: 2rem;
}

.loading, .empty {
  text-align: center;
  padding: 3rem;
  font-size: 1.2rem;
}

.empty .btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.8rem 2rem;
  background: #3498db;
  color: white;
  border-radius: 8px;
  text-decoration: none;
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.order-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #eee;
}

.order-header h3 {
  margin: 0 0 0.5rem 0;
}

.date {
  color: #666;
  font-size: 0.9rem;
  margin: 0;
}

.status {
  padding: 0.4rem 1rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status.pending { background: #fff3cd; color: #856404; }
.status.processing { background: #cce5ff; color: #004085; }
.status.shipped { background: #d1ecf1; color: #0c5460; }
.status.delivered { background: #d4edda; color: #155724; }
.status.cancelled { background: #f8d7da; color: #721c24; }

.order-items {
  margin-bottom: 1.5rem;
}

.order-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 0.5rem;
}

.order-item img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 6px;
}

.item-info {
  flex: 1;
}

.item-info h4 {
  margin: 0 0 0.25rem 0;
  font-size: 1rem;
}

.item-info p {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.item-total {
  font-weight: 600;
  color: #2c3e50;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.order-total {
  font-size: 1.1rem;
}

.view-details {
  padding: 0.6rem 1.5rem;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.view-details:hover {
  background: #2980b9;
}

@media (max-width: 768px) {
  .order-header {
    flex-direction: column;
    gap: 1rem;
  }
  
  .order-item {
    flex-direction: column;
    text-align: center;
  }
  
  .order-footer {
    flex-direction: column;
    gap: 1rem;
  }
  
  .view-details {
    width: 100%;
  }
}
</style>