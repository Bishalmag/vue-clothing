<template>
  <div class="my-orders">
    <main class="container">
      <section class="orders-section">
        <h2>My Orders</h2>
        <p class="muted">Track your recent orders</p>

        <div v-if="loading" class="loading">Loading orders...</div>
        <div v-else-if="error" class="error">
          <p>Failed to load orders</p>
          <button @click="fetchOrders">Retry</button>
        </div>
        <div v-else-if="orders.length === 0" class="no-orders">
          <div class="icon">📦</div>
          <p>No orders yet</p>
          <button class="shop-btn" @click="goShopping">Start Shopping</button>
        </div>

        <div v-else class="orders-list">
          <div class="order-card" v-for="order in orders" :key="order.id">
            <div class="order-header">
              <span>Order #{{ order.id }}</span>
              <button class="details-btn" @click="openDetails(order)">View Details</button>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Modal -->
    <div v-if="detailsOrder" class="modal-backdrop" @click.self="closeDetails">
      <div class="modal">
        <button class="modal-close" @click="closeDetails">✕</button>
        <div class="modal-content">
          <h3>Order #{{ detailsOrder.id }} Details</h3>
          <p>Status: {{ getStatusText(detailsOrder.status) }}</p>
          <p>Total Amount: ${{ formatPrice(detailsOrder.total_amount) }}</p>
          <p>Date: {{ formatDate(detailsOrder.created_at) }}</p>

          <div class="modal-items">
            <div class="modal-item" v-for="item in detailsOrder.items" :key="item.id || item.product_id">
              <img :src="item.image || '/images/no-image.png'" alt="Product image" />
              <div>
                <h4>{{ item.name }}</h4>
                <p class="muted">{{ item.category }} <span v-if="item.size">• {{ item.size }}</span><span v-if="item.color">• {{ item.color }}</span></p>
                <p>Qty: {{ item.quantity }}</p>
                <p>Price: ${{ formatPrice(item.total_price) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/axios'

const orders = ref([])
const loading = ref(true)
const error = ref(false)
const detailsOrder = ref(null)
const router = useRouter()

const fetchOrders = async () => {
  loading.value = true
  error.value = false
  try {
    const res = await api.get('/orders')
    orders.value = res.data?.data || []
  } catch (err) {
    console.error(err)
    error.value = true
    orders.value = []
  } finally {
    loading.value = false
  }
}

const formatPrice = (v) => (Number(v) || 0).toFixed(2)
const formatDate = (date) => new Date(date).toLocaleDateString()
const getStatusText = (status) => ({0:'Pending',1:'Shipped',2:'Cancelled',}[status] || 'Pending')
const goShopping = () => router.push('/')
const openDetails = (order) => detailsOrder.value = order
const closeDetails = () => detailsOrder.value = null

onMounted(fetchOrders)
</script>

<style scoped>
.my-orders {
  min-height: 100vh;
  background: #f6f8fb;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
}

.orders-section h2 {
  margin-bottom: 0.25rem;
}

.muted {
  color: #6b7280;
}

.loading {
  text-align: center;
  padding: 2rem;
}

.error {
  text-align: center;
  padding: 2rem;
}

.error button {
  background: #dc3545;
  color: white;
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.shop-btn {
  background: #007bff;
  color: white;
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.shop-btn:hover {
  background: #0056b3;
}

.details-btn:hover {
  background: #0056b3;
}

.no-orders {
  background: white;
  padding: 2rem;
  text-align: center;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.order-card {
  background: white;
  padding: 1.25rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 15, 25, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 1rem;
}

.modal {
  background: white;
  width: min(800px, 90%);
  border-radius: 10px;
  position: relative;
  padding: 1.5rem;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}

.modal-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 1rem;
}

.modal-item {
  display: flex;
  gap: 1rem;
  background: #f9fafb;
  padding: 0.75rem;
  border-radius: 6px;
}

.modal-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 6px;
  background: #e5e7eb;
}

.modal-item h4 {
  margin: 0 0 0.25rem;
  color: #1f2d3d;
}

.modal-item p {
  margin: 0.25rem 0;
  color: #555;
}

@media (max-width: 768px) {
  .modal-item {
    flex-direction: column;
  }
  
  .modal-item img {
    width: 100%;
    height: 180px;
  }
}
</style>
