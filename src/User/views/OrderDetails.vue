<template>
  <div class="order-details">
    <!-- Header -->
    <div class="order-header">
      <div>
        <h1>Order #{{ order.id }}</h1>
        <p class="order-date">Placed {{ formatDate(order.orderDate) }}</p>
      </div>
      <div class="status" :class="order.status">{{ getStatusText(order.status) }}</div>
    </div>

    <!-- Progress -->
    <div class="progress">
      <div 
        v-for="step in progressSteps" 
        :key="step.status"
        :class="['step', { active: isStepActive(step.status), completed: isStepCompleted(step.status) }]"
      >
        <div class="dot"></div>
        <span>{{ step.label }}</span>
      </div>
    </div>

    <!-- Info Cards -->
    <div class="info-grid">
      <div class="card">
        <h3>Shipping</h3>
        <div class="address">
          <p><strong>{{ order.shippingAddress.name }}</strong></p>
          <p>{{ order.shippingAddress.street }}</p>
          <p>{{ order.shippingAddress.city }}, {{ order.shippingAddress.state }} {{ order.shippingAddress.zipCode }}</p>
          <p class="phone">{{ order.shippingAddress.phone }}</p>
        </div>
      </div>

      <div class="card">
        <h3>Payment</h3>
        <div class="payment">
          <p><strong>{{ order.payment.method }}</strong></p>
          <p>**** {{ order.payment.last4 }}</p>
          <p class="date">Paid {{ formatDate(order.payment.paidAt) }}</p>
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="items-section">
      <h2>Items ({{ order.items.length }})</h2>
      <div class="items">
        <div 
          v-for="item in order.items" 
          :key="item.id" 
          class="item"
        >
          <img :src="item.image" :alt="item.name" />
          <div class="details">
            <h4>{{ item.name }}</h4>
            <p class="meta">{{ item.category }} • Size: {{ item.size }}</p>
            <p class="quantity">Qty: {{ item.quantity }}</p>
          </div>
          <div class="price">${{ (item.price * item.quantity).toFixed(2) }}</div>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="summary">
      <div class="row">
        <span>Subtotal</span>
        <span>${{ order.summary.subtotal.toFixed(2) }}</span>
      </div>
      <div class="row">
        <span>Shipping</span>
        <span>${{ order.summary.shipping.toFixed(2) }}</span>
      </div>
      <div class="row">
        <span>Tax</span>
        <span>${{ order.summary.tax.toFixed(2) }}</span>
      </div>
      <div class="divider"></div>
      <div class="row total">
        <span>Total</span>
        <span>${{ order.summary.total.toFixed(2) }}</span>
      </div>
    </div>

    <!-- Actions -->
    <div class="actions">
      <button class="btn primary" @click="trackOrder">Track Order</button>
      <button class="btn" @click="downloadInvoice">Invoice</button>
      <button class="btn" @click="contactSupport">Help</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const order = ref({
  id: 'ORD-2024-00123',
  status: 'shipped',
  orderDate: '2024-01-15T10:30:00Z',
  items: [
    {
      id: 1,
      name: 'Classic White T-Shirt',
      category: 'T-Shirts',
      price: 29.99,
      quantity: 2,
      size: 'M',
      image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=150'
    },
    {
      id: 2,
      name: 'Slim Fit Jeans',
      category: 'Jeans',
      price: 79.99,
      quantity: 1,
      size: '32x32',
      image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=150'
    }
  ],
  shippingAddress: {
    name: 'John Doe',
    street: '123 Main Street',
    city: 'New York',
    state: 'NY',
    zipCode: '10001',
    phone: '+1 (555) 123-4567'
  },
  payment: {
    method: 'Visa Credit Card',
    last4: '4242',
    paidAt: '2024-01-15T10:31:00Z'
  },
  summary: {
    subtotal: 139.97,
    shipping: 9.99,
    tax: 11.20,
    total: 161.16
  }
})

const progressSteps = [
  { status: 'confirmed', label: 'Confirmed' },
  { status: 'processing', label: 'Processing' },
  { status: 'shipped', label: 'Shipped' },
  { status: 'delivered', label: 'Delivered' }
]

const isStepActive = (stepStatus) => order.value.status === stepStatus
const isStepCompleted = (stepStatus) => {
  const statusOrder = ['confirmed', 'processing', 'shipped', 'delivered']
  return statusOrder.indexOf(stepStatus) < statusOrder.indexOf(order.value.status)
}

const getStatusText = (status) => {
  const statusMap = {
    confirmed: 'Confirmed',
    processing: 'Processing',
    shipped: 'Shipped',
    delivered: 'Delivered'
  }
  return statusMap[status] || status
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const trackOrder = () => alert('Tracking order...')
const downloadInvoice = () => alert('Downloading invoice...')
const contactSupport = () => alert('Contacting support...')
</script>

<style scoped>
.order-details {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

/* Header */
.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 2rem;
}

.order-header h1 {
  font-size: 1.5rem;
  color: #1a1a1a;
  margin: 0 0 0.25rem 0;
}

.order-date {
  color: #666;
  margin: 0;
}

.status {
  padding: 0.5rem 1rem;
  border-radius: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.status.confirmed { background: #e3f2fd; color: #1976d2; }
.status.processing { background: #fff3e0; color: #f57c00; }
.status.shipped { background: #e8f5e8; color: #2e7d32; }
.status.delivered { background: #e8f5e8; color: #2e7d32; }

/* Progress */
.progress {
  display: flex;
  justify-content: space-between;
  margin-bottom: 2rem;
  position: relative;
}

.progress::before {
  content: '';
  position: absolute;
  top: 12px;
  left: 0;
  right: 0;
  height: 2px;
  background: #e0e0e0;
  z-index: 1;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  z-index: 2;
}

.dot {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #e0e0e0;
  margin-bottom: 0.5rem;
}

.step.completed .dot { background: #4caf50; }
.step.active .dot { background: #2196f3; border: 3px solid #bbdefb; }

.step span {
  font-size: 0.875rem;
  color: #666;
}

.step.active span { color: #2196f3; font-weight: 500; }
.step.completed span { color: #4caf50; }

/* Info Grid */
.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.card {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  border: 1px solid #e0e0e0;
}

.card h3 {
  margin: 0 0 1rem 0;
  color: #1a1a1a;
  font-size: 1rem;
}

.address p, .payment p {
  margin: 0.25rem 0;
  color: #666;
}

.phone, .date {
  margin-top: 0.5rem !important;
  color: #2196f3 !important;
}

/* Items */
.items-section {
  margin-bottom: 2rem;
}

.items-section h2 {
  font-size: 1.25rem;
  color: #1a1a1a;
  margin-bottom: 1rem;
}

.item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.item:last-child {
  border-bottom: none;
}

.item img {
  width: 60px;
  height: 60px;
  border-radius: 0.375rem;
  object-fit: cover;
}

.details {
  flex: 1;
}

.details h4 {
  margin: 0 0 0.25rem 0;
  color: #1a1a1a;
  font-size: 1rem;
}

.meta, .quantity {
  margin: 0;
  color: #666;
  font-size: 0.875rem;
}

.price {
  font-weight: 600;
  color: #1a1a1a;
}

/* Summary */
.summary {
  background: white;
  padding: 1.5rem;
  border-radius: 0.5rem;
  border: 1px solid #e0e0e0;
  margin-bottom: 2rem;
}

.row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  color: #666;
}

.row.total {
  font-weight: 600;
  color: #1a1a1a;
  font-size: 1.125rem;
}

.divider {
  height: 1px;
  background: #e0e0e0;
  margin: 1rem 0;
}

/* Actions */
.actions {
  display: flex;
  gap: 1rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: 1px solid #e0e0e0;
  background: white;
  border-radius: 0.375rem;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

.btn.primary {
  background: #2196f3;
  color: white;
  border-color: #2196f3;
}

.btn:hover {
  background: #f5f5f5;
}

.btn.primary:hover {
  background: #1976d2;
}

/* Responsive */
@media (max-width: 768px) {
  .order-details {
    padding: 1rem;
  }
  
  .order-header {
    flex-direction: column;
    gap: 1rem;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .actions {
    flex-direction: column;
  }
  
  .progress {
    gap: 0.5rem;
  }
  
  .step span {
    font-size: 0.75rem;
    text-align: center;
  }
}
</style>