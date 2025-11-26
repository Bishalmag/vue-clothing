<template>
  <div class="admin-orders">
    <!-- Header -->
    <div class="page-header">
      <h1>Orders Management</h1>
      <p>Manage and track all customer orders</p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-content">
          <h3>TOTAL ORDERS</h3>
          <div class="stat-value">324</div>
        </div>
        <div class="stat-icon">📦</div>
      </div>
      <div class="stat-card">
        <div class="stat-content">
          <h3>PENDING</h3>
          <div class="stat-value">12</div>
        </div>
        <div class="stat-icon">⏳</div>
      </div>
      <div class="stat-card">
        <div class="stat-content">
          <h3>COMPLETED</h3>
          <div class="stat-value">289</div>
        </div>
        <div class="stat-icon">✅</div>
      </div>
      <div class="stat-card">
        <div class="stat-content">
          <h3>REVENUE</h3>
          <div class="stat-value">$12,458</div>
        </div>
        <div class="stat-icon">💰</div>
      </div>
    </div>

    <!-- Filters and Search -->
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

    <!-- Orders Table -->
    <div class="orders-table-container">
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
                <strong>{{ order.customer.name }}</strong>
                <span>{{ order.customer.email }}</span>
              </div>
            </td>
            <td class="date">{{ formatDate(order.date) }}</td>
            <td class="amount">${{ order.amount }}</td>
            <td>
              <span :class="['status', order.status]">
                {{ order.status }}
              </span>
            </td>
            <td class="actions">
              <button class="action-btn view" @click="viewOrder(order)" title="View Details">
                👁️
              </button>
              <button class="action-btn edit" @click="editOrder(order)" title="Edit Order">
                ✏️
              </button>
              <button class="action-btn delete" @click="deleteOrder(order)" title="Delete Order">
                🗑️
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div v-if="filteredOrders.length === 0" class="empty-state">
      <div class="empty-icon">📦</div>
      <h3>No orders found</h3>
      <p>There are no orders matching your current filters.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const activeFilter = ref('all')

const filters = [
  { label: 'All Orders', value: 'all' },
  { label: 'Pending', value: 'pending' },
  { label: 'Processing', value: 'processing' },
  { label: 'Shipped', value: 'shipped' },
  { label: 'Delivered', value: 'delivered' },
  { label: 'Cancelled', value: 'cancelled' }
]

// Mock data - replace with actual API call
const orders = ref([
  {
    id: 'ORD-001',
    customer: { name: 'John Doe', email: 'john@example.com' },
    date: '2024-01-15',
    amount: 129.99,
    status: 'pending',
    items: [
      { name: 'Classic White Tee', quantity: 2, price: 29.99 },
      { name: 'Denim Jacket', quantity: 1, price: 69.99 }
    ]
  },
  {
    id: 'ORD-002',
    customer: { name: 'Jane Smith', email: 'jane@example.com' },
    date: '2024-01-14',
    amount: 89.99,
    status: 'processing',
    items: [
      { name: 'Urban Cargo Pants', quantity: 1, price: 65.00 },
      { name: 'Minimalist Sneaker', quantity: 1, price: 24.99 }
    ]
  },
  {
    id: 'ORD-003',
    customer: { name: 'Mike Johnson', email: 'mike@example.com' },
    date: '2024-01-13',
    amount: 45.50,
    status: 'shipped',
    items: [
      { name: 'Classic White Tee', quantity: 1, price: 29.99 },
      { name: 'Baseball Cap', quantity: 1, price: 15.51 }
    ]
  },
  {
    id: 'ORD-004',
    customer: { name: 'Sarah Wilson', email: 'sarah@example.com' },
    date: '2024-01-12',
    amount: 199.99,
    status: 'delivered',
    items: [
      { name: 'Winter Coat', quantity: 1, price: 129.99 },
      { name: 'Wool Scarf', quantity: 2, price: 35.00 }
    ]
  },
  {
    id: 'ORD-005',
    customer: { name: 'David Brown', email: 'david@example.com' },
    date: '2024-01-11',
    amount: 75.25,
    status: 'cancelled',
    items: [
      { name: 'Sports Shorts', quantity: 3, price: 25.08 }
    ]
  }
])

const filteredOrders = computed(() => {
  let filtered = orders.value

  // Filter by status
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(order => order.status === activeFilter.value)
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(order => 
      order.id.toLowerCase().includes(query) ||
      order.customer.name.toLowerCase().includes(query) ||
      order.customer.email.toLowerCase().includes(query)
    )
  }

  return filtered
})

const setFilter = (filter) => {
  activeFilter.value = filter
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const viewOrder = (order) => {
  alert(`Viewing order: ${order.id}`)
  // Navigate to order details page
}

const editOrder = (order) => {
  alert(`Editing order: ${order.id}`)
  // Open edit modal or navigate to edit page
}

const deleteOrder = (order) => {
  if (confirm(`Are you sure you want to delete order ${order.id}?`)) {
    orders.value = orders.value.filter(o => o.id !== order.id)
  }
}
</script>

<style scoped>
.admin-orders {
  padding: 2rem;
  background: linear-gradient(135deg, #1f1f47, #000);
  min-height: 100vh;
  font-family: 'Poppins', sans-serif;
  color: #fff;
}

/* Header */
.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 600;
  margin: 0 0 0.5rem 0;
  background: linear-gradient(90deg, #00eaff, #00ff88);
  -webkit-text-fill-color: transparent;
}

.page-header p {
  color: #e0e0e0;
  margin: 0;
  font-size: 1.1rem;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(10px);
  padding: 1.5rem;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid rgba(255, 255, 255, 0.15);
  transition: 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.3);
  border-color: #00eaff;
}

.stat-content h3 {
  font-size: 0.8rem;
  font-weight: 600;
  color: #00eaff;
  margin: 0 0 0.5rem 0;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0;
  color: #fff;
}

.stat-icon {
  font-size: 2rem;
  opacity: 0.8;
}

/* Filters */
.filters-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  gap: 1rem;
}

.search-box {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  color: #fff;
  font-size: 1rem;
  backdrop-filter: blur(10px);
}

.search-input:focus {
  outline: none;
  border-color: #00eaff;
  box-shadow: 0 0 0 2px rgba(0, 234, 255, 0.2);
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
}

.filter-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  color: #e0e0e0;
  cursor: pointer;
  transition: 0.2s;
  font-size: 0.9rem;
}

.filter-btn:hover {
  background: rgba(0, 255, 255, 0.12);
  border-color: #00eaff;
}

.filter-btn.active {
  background: rgba(0, 255, 255, 0.2);
  border-color: #00eaff;
  color: #00eaff;
}

/* Table */
.orders-table-container {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(12px);
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  overflow: hidden;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
}

.orders-table th {
  background: rgba(0, 0, 0, 0.3);
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: #00eaff;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.orders-table td {
  padding: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.orders-table tr:hover {
  background: rgba(255, 255, 255, 0.05);
}

.order-id {
  font-weight: 600;
  color: #00eaff;
}

.customer-info {
  display: flex;
  flex-direction: column;
}

.customer-info strong {
  color: #fff;
}

.customer-info span {
  font-size: 0.8rem;
  color: #9ca3af;
}

.date, .amount {
  color: #e0e0e0;
}

.amount {
  font-weight: 600;
  color: #00ff88;
}

/* Status Badges */
.status {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status.pending {
  background: rgba(255, 193, 7, 0.2);
  color: #ffc107;
  border: 1px solid rgba(255, 193, 7, 0.3);
}

.status.processing {
  background: rgba(33, 150, 243, 0.2);
  color: #2196f3;
  border: 1px solid rgba(33, 150, 243, 0.3);
}

.status.shipped {
  background: rgba(156, 39, 176, 0.2);
  color: #9c27b0;
  border: 1px solid rgba(156, 39, 176, 0.3);
}

.status.delivered {
  background: rgba(76, 175, 80, 0.2);
  color: #4caf50;
  border: 1px solid rgba(76, 175, 80, 0.3);
}

.status.cancelled {
  background: rgba(244, 67, 54, 0.2);
  color: #f44336;
  border: 1px solid rgba(244, 67, 54, 0.3);
}

/* Actions */
.actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 0.5rem;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.2s;
  font-size: 1rem;
}

.action-btn.view:hover {
  background: rgba(33, 150, 243, 0.3);
  border-color: #2196f3;
}

.action-btn.edit:hover {
  background: rgba(255, 193, 7, 0.3);
  border-color: #ffc107;
}

.action-btn.delete:hover {
  background: rgba(244, 67, 54, 0.3);
  border-color: #f44336;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem;
  color: #9ca3af;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state h3 {
  color: #e0e0e0;
  margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
  .admin-orders {
    padding: 1rem;
  }

  .filters-section {
    flex-direction: column;
    align-items: stretch;
  }

  .search-box {
    max-width: none;
  }

  .filter-buttons {
    justify-content: center;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .orders-table-container {
    overflow-x: auto;
  }

  .orders-table {
    min-width: 800px;
  }
}
</style>