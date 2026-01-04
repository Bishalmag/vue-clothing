<template>
  <div class="admin-orders">
    <div class="page-header">
      <h1>Orders Management</h1>
      <p>Manage and track all customer orders</p>
    </div>

    <div class="filters-section">
      <div class="search-box">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search orders..." 
          class="search-input"
        >
        <span class="search-icon">🔍</span>
      </div>
      
      <div class="filter-buttons">
        <button 
          v-for="filter in filters" 
          :key="filter.value"
          :class="['filter-btn', { active: activeFilter === filter.value }]"
          @click="setFilter(filter.value)"
        >
          {{ filter.label }}
        </button>
      </div>
    </div>

    <div class="orders-table-container" v-if="orders.length">
      <table class="orders-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in filteredOrders" :key="order.id">
            <td class="order-id">#{{ order.id }}</td>
            <td class="customer">
              <div class="customer-info">
                <strong>{{ order.user?.name || 'Unknown' }}</strong>
                <span>{{ order.user?.email || 'No email' }}</span>
              </div>
            </td>
            <td class="date">{{ formatDate(order.created_at) }}</td>
            <td class="amount">${{ formatPrice(order.total_amount) }}</td>
            <td>
              <span :class="['status', getStatusClass(order.status)]">
                {{ getStatusText(order.status) }}
              </span>
            </td>
            <td class="actions">
              <button class="action-btn view" @click="viewOrder(order)">👁️</button>
              <button class="action-btn edit" @click="editOrder(order)">✏️</button>
              <button class="action-btn delete" @click="deleteOrder(order)">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-state">
      <div class="empty-icon">📦</div>
      <h3>No orders found</h3>
      <p>There are no orders matching your current filters.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])
const searchQuery = ref('')
const activeFilter = ref('all')

const filters = [
  { label: 'All Orders', value: 'all' },
  { label: 'Pending', value: '0' },
  { label: 'Shipped', value: '1' },
  { label: 'Cancelled', value: '2' }
]

const fetchOrders = async () => {
  try {
    const res = await axios.get('/api/admin/orders', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('adminToken')}`
      }
    })
    orders.value = res.data.data || []
  } catch (err) {
    console.error('Failed to fetch orders:', err)
    orders.value = []
  }
}

onMounted(() => {
  fetchOrders()
})

const filteredOrders = computed(() => {
  let filtered = orders.value

  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(o => o.status.toString() === activeFilter.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(o =>
      o.id.toString().toLowerCase().includes(query) ||
      (o.user?.name || '').toLowerCase().includes(query) ||
      (o.user?.email || '').toLowerCase().includes(query)
    )
  }

  return filtered
})

const setFilter = (filter) => {
  activeFilter.value = filter
}

const formatPrice = (value) => {
  const num = parseFloat(value)
  return isNaN(num) ? '0.00' : num.toFixed(2)
}

const formatDate = (dateString) => {
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  } catch {
    return dateString
  }
}

const getStatusText = (status) => {
  const statusMap = {
    0: 'Pending',
    1: 'Shipped',
    2: 'Cancelled'
  }
  return statusMap[status] || 'Unknown'
}

const getStatusClass = (status) => {
  const classMap = {
    0: 'pending',
    1: 'shipped',
    2: 'cancelled'
  }
  return classMap[status] || 'pending'
}

const viewOrder = (order) => {
  alert(`Viewing order: ${order.id}`)
}

const editOrder = (order) => {
  const newStatus = prompt(`Change status for order ${order.id}:\n0 = Pending\n1 = Shipped\n2 = Cancelled`, order.status)
  if (newStatus !== null) {
    updateOrderStatus(order.id, parseInt(newStatus))
  }
}

const updateOrderStatus = async (orderId, status) => {
  try {
    await axios.put(`/api/admin/orders/${orderId}`, {
      status: status
    }, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('adminToken')}`
      }
    })
    
    // Update local state
    const index = orders.value.findIndex(o => o.id === orderId)
    if (index !== -1) {
      orders.value[index].status = status
    }
  } catch (err) {
    console.error('Failed to update order:', err)
    alert('Failed to update order status')
  }
}

const deleteOrder = async (order) => {
  if (!confirm(`Are you sure you want to delete order ${order.id}?`)) return

  try {
    await axios.delete(`/api/admin/orders/${order.id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('adminToken')}`
      }
    })
    orders.value = orders.value.filter(o => o.id !== order.id)
  } catch (err) {
    console.error('Failed to delete order:', err)
    alert('Failed to delete order')
  }
}
</script>

<style scoped>
.admin-orders {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 2rem;
  color: #333;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #666;
}

.filters-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  flex: 1;
  max-width: 300px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

.filter-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
}

.filter-btn.active {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.orders-table-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  overflow: hidden;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
}

.orders-table thead {
  background: #f8f9fa;
}

.orders-table th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: #333;
  border-bottom: 2px solid #e9ecef;
}

.orders-table td {
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.order-id {
  font-weight: 600;
  color: #007bff;
}

.customer-info {
  display: flex;
  flex-direction: column;
}

.customer-info strong {
  color: #333;
}

.customer-info span {
  font-size: 0.9rem;
  color: #666;
}

.date {
  color: #666;
}

.amount {
  font-weight: 600;
  color: #333;
}

.status {
  padding: 0.25rem 0.75rem;
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

.actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  width: 36px;
  height: 36px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-btn.view {
  background: #6c757d;
  color: white;
}

.action-btn.edit {
  background: #ffc107;
  color: white;
}

.action-btn.delete {
  background: #dc3545;
  color: white;
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  color: #333;
  margin-bottom: 0.5rem;
}

.empty-state p {
  color: #666;
}

@media (max-width: 768px) {
  .admin-orders {
    padding: 1rem;
  }
  
  .filters-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-box {
    max-width: 100%;
  }
  
  .orders-table {
    display: block;
    overflow-x: auto;
  }
}
</style>