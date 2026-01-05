<template>
  <div class="admin-orders">
    <h1>Orders</h1>

    <div v-if="loading" class="state">Loading orders...</div>
    <div v-else-if="error" class="state error">{{ error }}</div>

    <table v-else class="orders-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Items</th>
          <th>Amount</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>#{{ order.id }}</td>

          <td>
            <div class="customer">
              <strong>{{ order.user?.first_name }} {{ order.user?.last_name }}</strong>
              <span>{{ order.user?.email }}</span>
            </div>
          </td>

          <td>{{ formatDate(order.created_at) }}</td>

          <td>{{ order.items?.length || 0 }}</td>

          <td>${{ formatPrice(order.total_amount) }}</td>

          <td>
            <div class="status-wrapper">
              <select
                class="status-dropdown"
                :value="order.status"
                @change="updateOrderStatus(order.id, $event.target.value)"
              >
                <option value="0">Pending</option>
                <option value="1">Shipped</option>
                <option value="2">Cancelled</option>
              </select>

              <span :class="['status', statusClass(order.status)]">
                {{ statusText(order.status) }}
              </span>
            </div>
          </td>

          <td>
            <button class="delete-btn" @click="deleteOrder(order.id)">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])
const loading = ref(false)
const error = ref(null)

const fetchOrders = async () => {
  loading.value = true
  error.value = null

  try {
    const token = localStorage.getItem('adminToken')

    const res = await axios.get('http://localhost:8000/api/admin/orders', {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
      }
    })

    orders.value = res.data.data || []
  } catch (err) {
    error.value = 'Failed to load orders'
  } finally {
    loading.value = false
  }
}

const updateOrderStatus = async (orderId, status) => {
  try {
    const token = localStorage.getItem('adminToken')

    await axios.put(
      `http://localhost:8000/api/admin/orders/${orderId}`,
      { status: Number(status) },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      }
    )

    const order = orders.value.find(o => o.id === orderId)
    if (order) order.status = Number(status)
  } catch {
    alert('Status update failed')
  }
}

const deleteOrder = async (orderId) => {
  if (!confirm('Delete this order?')) return

  try {
    const token = localStorage.getItem('adminToken')

    await axios.delete(`http://localhost:8000/api/admin/orders/${orderId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    orders.value = orders.value.filter(o => o.id !== orderId)
  } catch {
    alert('Delete failed')
  }
}

const formatPrice = (value) => {
  const n = parseFloat(value)
  return isNaN(n) ? '0.00' : n.toFixed(2)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const statusText = (status) => {
  return {
    0: 'Pending',
    1: 'Shipped',
    2: 'Cancelled',
  }[status] || 'Unknown'
}

const statusClass = (status) => {
  return {
    0: 'pending',
    1: 'shipped',
    2: 'cancelled',
  }[status]
}

onMounted(fetchOrders)
</script>

<style scoped>
.admin-orders {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
}

h1 {
  margin-bottom: 1.5rem;
}

.state {
  padding: 2rem;
  text-align: center;
}

.state.error {
  color: #c00;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
}

.orders-table th {
  padding: 1rem;
  background: #f4f6f8;
  text-align: left;
  border-bottom: 2px solid #ddd;
}

.orders-table td {
  padding: 1rem;
  border-bottom: 1px solid #eee;
}

.customer {
  display: flex;
  flex-direction: column;
}

.customer span {
  font-size: 0.85rem;
  color: #666;
}

.status-wrapper {
  display: flex;
  align-items: center;
  gap: 10px;
}

.status-dropdown {
  padding: 6px 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  background: #fff;
  cursor: pointer;
}

.status {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status.pending {
  background: #fff3cd;
  color: #856404;
}

.status.shipped {
  background: #d4edda;
  color: #155724;
}

.status.cancelled {
  background: #f8d7da;
  color: #721c24;
}

.delete-btn {
  background: #dc3545;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
}

.delete-btn:hover {
  background: #c82333;
}
</style>
