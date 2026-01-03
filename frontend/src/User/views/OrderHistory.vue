<template>
  <div class="order-history-page">
    <div class="page-header">
      <h1>Order History</h1>
      <p>Your complete order history</p>
    </div>

    <div class="history-content">
      <!-- Empty State -->
      <div v-if="orders.length === 0" class="empty-history">
        <div class="empty-icon">📋</div>
        <h2>No order history</h2>
        <p>Your past orders will appear here</p>
        <router-link to="/" class="shop-btn">
          Start Shopping
        </router-link>
      </div>

      <!-- Orders Table -->
      <div v-else class="orders-table">
        <div class="table-header">
          <div class="row">
            <div>Order ID</div>
            <div>Date</div>
            <div>Items</div>
            <div>Total</div>
            <div>Status</div>
            <div>Action</div>
          </div>
        </div>

        <div class="table-body">
          <div v-for="order in orders" :key="order.id" class="table-row">
            <div class="order-id">#{{ order.id }}</div>
            <div class="order-date">{{ order.date }}</div>
            <div class="order-items">{{ order.items }} items</div>
            <div class="order-total">${{ order.total }}</div>
            <div class="order-status" :class="order.status">
              {{ order.status }}
            </div>
            <div class="order-action">
              <button @click="viewOrder(order.id)" class="view-btn">
                View
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

// Sample order history data
const orders = ref([
  {
    id: 'ORD-789',
    date: 'Nov 18, 2024',
    items: 3,
    total: 189.97,
    status: 'delivered'
  },
  {
    id: 'ORD-456',
    date: 'Nov 12, 2024',
    items: 2,
    total: 109.98,
    status: 'delivered'
  },
  {
    id: 'ORD-123',
    date: 'Nov 5, 2024',
    items: 1,
    total: 79.99,
    status: 'delivered'
  },
  {
    id: 'ORD-999',
    date: 'Oct 28, 2024',
    items: 4,
    total: 239.96,
    status: 'delivered'
  },
  {
    id: 'ORD-888',
    date: 'Oct 15, 2024',
    items: 2,
    total: 149.98,
    status: 'delivered'
  }
])

const viewOrder = (orderId) => {
  router.push(`/order/${orderId}`)
}
</script>

<style scoped>
.order-history-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem;
}

.page-header {
  text-align: center;
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 2rem;
  color: #344767;
  margin-bottom: 0.5rem;
}

.page-header p {
  color: #6c757d;
}

/* Empty State */
.empty-history {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-history h2 {
  color: #344767;
  margin-bottom: 0.5rem;
}

.empty-history p {
  color: #6c757d;
  margin-bottom: 2rem;
}

.shop-btn {
  background: #3498db;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
}

.shop-btn:hover {
  background: #2980b9;
}

/* Orders Table */
.orders-table {
  background: white;
  border-radius: 12px;
  border: 1px solid #eef2f6;
  overflow: hidden;
}

.table-header {
  background: #f8f9fa;
  padding: 1rem;
  font-weight: 600;
  color: #344767;
}

.table-header .row,
.table-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr auto;
  gap: 1rem;
  align-items: center;
}

.table-body {
  padding: 0 1rem;
}

.table-row {
  padding: 1rem 0;
  border-bottom: 1px solid #eef2f6;
}

.table-row:last-child {
  border-bottom: none;
}

.order-id {
  font-weight: 600;
  color: #3498db;
}

.order-date,
.order-items,
.order-total {
  color: #6c757d;
}

.order-total {
  font-weight: 600;
  color: #344767;
}

.order-status {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
  text-transform: capitalize;
  text-align: center;
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

.view-btn {
  background: #3498db;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 500;
}

.view-btn:hover {
  background: #2980b9;
}

/* Responsive */
@media (max-width: 768px) {
  .order-history-page {
    padding: 1rem;
  }
  
  .table-header .row,
  .table-row {
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
  }
  
  .table-header .row div:nth-child(n+4),
  .table-row div:nth-child(n+4) {
    display: none;
  }
}
</style>