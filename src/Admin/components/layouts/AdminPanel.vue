<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const revenuePeriod = ref('7d')

// --- SVG Icons (Consistent with previous components) ---
const icons = {
  plus: "M12 5v14M5 12h14",
  package: "M12 10a4 4 0 0 0 0 8c2.14 0 4-1.63 4-4s-1.86-4-4-4zM21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z",
  users: "M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M16 3.13a4 4 0 0 1 0 7.75",
  tag: "M20.5 10l-12 12c-.5.5-1 .5-1.5 0L1 14.5c-.5-.5-.5-1 0-1.5l12-12c.5-.5 1-.5 1.5 0l5 5c.5.5.5 1 0 1.5z M14.5 5.5h-9",
  dollar: "M12 1v22M17 5H7a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3z",
  chart: "M18 20V10M12 20V4M6 20v-6",
  alert: "M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z",
  star: "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z",
  settings: "M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.18-.08a2 2 0 0 0-2 2v.44a2 2 0 0 0 2 2h.18a2 2 0 0 1 1.73 1l.25.43a2 2 0 0 1 0 2l-.08.18a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.18.08a2 2 0 0 0 2-2v-.44a2 2 0 0 0-2-2h-.18a2 2 0 0 1-1.73-1l-.25-.43a2 2 0 0 1 0-2l.08-.18a2 2 0 0 0-2-2z M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
}

// Logic for fake chart data
const revenueBars = computed(() => {
  const periods = {
    '7d': ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    '30d': ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'],
    '90d': ['Mth 1', 'Mth 2', 'Mth 3']
  }

  return periods[revenuePeriod.value].map(label => ({
    label,
    height: `${Math.floor(Math.random() * 60) + 20}%`
  }))
})

// Quick Action Router Logic
const quickAction = (action) => {
  const routes = {
    'add-product': '/admin/products/add',
    'view-orders': '/admin/orders',
    'manage-orders': '/admin/orders',
    'view-customers': '/admin/customers',
    'analytics': '/admin/analytics',
    'inventory': '/admin/inventory',
    'settings': '/admin/settings'
  }

  if (routes[action]) {
    router.push(routes[action])
  }
}

// Helper component for rendering SVGs
const SvgIcon = {
  props: ['d', 'size', 'color'],
  template: `
    <svg :viewBox="'0 0 24 24'" :width="size || 20" :height="size || 20" 
         stroke="currentColor" stroke-width="2" fill="none" 
         stroke-linecap="round" stroke-linejoin="round" :style="{ color: color }">
      <path :d="d" />
    </svg>
  `
}
</script>

<template>
  <div class="admin-dashboard">
    
    <div class="welcome-section">
      <div class="welcome-content">
        <h1>Welcome back, Admin!</h1>
        <p>A concise overview of your **StyleStore** today.</p>
      </div>
      <div class="welcome-actions">
        <button class="action-btn" @click="quickAction('add-product')">
          <SvgIcon :d="icons.plus" size="18" />
          Add Product
        </button>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon-box primary">
          <SvgIcon :d="icons.dollar" size="24" color="white" />
        </div>
        <div class="stat-header">
          <h3>Total Revenue</h3>
          <span class="stat-trend positive">+12%</span>
        </div>
        <div class="stat-value">$12,458</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon-box accent">
          <SvgIcon :d="icons.package" size="24" color="white" />
        </div>
        <div class="stat-header">
          <h3>Total Orders</h3>
          <span class="stat-trend positive">+8%</span>
        </div>
        <div class="stat-value">324</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon-box success">
          <SvgIcon :d="icons.users" size="24" color="white" />
        </div>
        <div class="stat-header">
          <h3>Customers</h3>
          <span class="stat-trend positive">+15%</span>
        </div>
        <div class="stat-value">1,258</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon-box warning">
          <SvgIcon :d="icons.tag" size="24" color="white" />
        </div>
        <div class="stat-header">
          <h3>Products</h3>
          <span class="stat-trend negative">-2%</span>
        </div>
        <div class="stat-value">56</div>
      </div>
    </div>

    <div class="main-grid">
      
      <div class="chart-card card">
        <div class="card-header">
          <h3>Revenue Overview</h3>
          <select v-model="revenuePeriod" class="period-select">
            <option value="7d">Last 7 Days</option>
            <option value="30d">Last 30 Days</option>
            <option value="90d">Last 90 Days</option>
          </select>
        </div>
        <div class="chart-bars-container">
          <div v-for="(bar, index) in revenueBars" :key="index" class="chart-bar">
            <div class="bar-fill" :style="{ height: bar.height }"></div>
            <span class="bar-label">{{ bar.label }}</span>
          </div>
        </div>
      </div>

      <div class="activity-card card">
        <div class="card-header">
          <h3>Recent Activity</h3>
          <router-link to="/admin/activity" class="view-all">View All</router-link>
        </div>

        <div class="activity-list">
          <div class="activity-item">
            <div class="activity-icon-box accent"><SvgIcon :d="icons.package" size="18" /></div>
            <div class="activity-content">
              <p>New order received from <strong>John Doe</strong></p>
              <span class="activity-time">2 min ago</span>
            </div>
            <span class="order-amount text-bold">$129.99</span>
          </div>

          <div class="activity-item">
            <div class="activity-icon-box success"><SvgIcon :d="icons.users" size="18" /></div>
            <div class="activity-content">
              <p>New customer registered: <strong>Jane Smith</strong></p>
              <span class="activity-time">15 min ago</span>
            </div>
          </div>

          <div class="activity-item">
            <div class="activity-icon-box danger"><SvgIcon :d="icons.alert" size="18" /></div>
            <div class="activity-content">
              <p><strong>Low stock</strong> alert for "Classic White Tee"</p>
              <span class="activity-time">1 hour ago</span>
            </div>
            <span class="stock-count text-bold">3 left</span>
          </div>

          <div class="activity-item">
            <div class="activity-icon-box warning"><SvgIcon :d="icons.star" size="18" /></div>
            <div class="activity-content">
              <p>New 5-star review for "Denim Jacket"</p>
              <span class="activity-time">2 hours ago</span>
            </div>
            <span class="rating text-bold">5.0</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="quick-actions-section card">
      <div class="card-header">
        <h3>Quick Access</h3>
      </div>
      <div class="actions-grid">
        <button class="quick-action-btn" @click="quickAction('add-product')">
          <SvgIcon :d="icons.plus" size="24" color="#4F46E5" />
          <span class="action-text">Add Product</span>
        </button>

        <button class="quick-action-btn" @click="quickAction('manage-orders')">
          <SvgIcon :d="icons.package" size="24" color="#4F46E5" />
          <span class="action-text">Manage Orders</span>
        </button>

        <button class="quick-action-btn" @click="quickAction('inventory')">
          <SvgIcon :d="icons.tag" size="24" color="#4F46E5" />
          <span class="action-text">Inventory</span>
        </button>
        
        <button class="quick-action-btn" @click="quickAction('view-customers')">
          <SvgIcon :d="icons.users" size="24" color="#4F46E5" />
          <span class="action-text">View Customers</span>
        </button>

        <button class="quick-action-btn" @click="quickAction('analytics')">
          <SvgIcon :d="icons.chart" size="24" color="#4F46E5" />
          <span class="action-text">Analytics</span>
        </button>
        
        <button class="quick-action-btn" @click="quickAction('settings')">
          <SvgIcon :d="icons.settings" size="24" color="#4F46E5" />
          <span class="action-text">Settings</span>
        </button>
      </div>
    </div>

  </div>
</template>

<style scoped>
/* --- VARIABLES --- */
:root {
  --color-accent: #4F46E5;    /* Indigo */
  --color-primary: #10B981;   /* Green/Success */
  --color-warning: #F59E0B;   /* Amber/Warning */
  --color-danger: #EF4444;    /* Red/Danger */
  --color-text-main: #1F2937; /* Dark Gray */
  --color-text-muted: #6B7280;/* Light Gray */
  --color-bg-light: #F9FAFB;
  --color-white: #ffffff;
  --card-border: 1px solid #E5E7EB;
}

.admin-dashboard {
  padding: 0;
  max-width: 1400px;
  margin: 0 auto;
}

.text-bold {
  font-weight: 600;
  color: var(--color-text-main);
}

/* --- CARDS & HEADERS --- */
.card {
  background: var(--color-white);
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  border: var(--card-border);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.card-header h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--color-text-main);
}

.view-all {
  color: var(--color-accent);
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
}

/* --- WELCOME SECTION --- */
.welcome-section {
  background-color: var(--color-white);
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-left: 6px solid var(--color-accent); /* Accent border */
}

.welcome-content h1 {
  margin: 0 0 0.25rem 0;
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--color-text-main);
}

.welcome-content p {
  margin: 0;
  color: var(--color-text-muted);
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: 1px solid var(--color-accent);
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  background: var(--color-accent);
  color: var(--color-white);
  transition: background-color 0.2s, transform 0.2s;
}

.action-btn:hover {
  background: #3c33cc; /* Darker accent */
  transform: translateY(-1px);
}

/* --- STATS GRID --- */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  padding: 1rem 1.5rem;
  position: relative;
}

.stat-icon-box {
  width: 40px;
  height: 40px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: -20px;
  right: 20px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.stat-icon-box.primary { background: var(--color-accent); }
.stat-icon-box.accent { background: #3B82F6; } /* Blue */
.stat-icon-box.success { background: var(--color-primary); }
.stat-icon-box.warning { background: var(--color-warning); }


.stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
  padding-top: 0.5rem; /* Space for the icon box */
}

.stat-header h3 {
  margin: 0;
  font-size: 0.75rem;
  font-weight: 500;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.stat-trend {
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.15rem 0.5rem;
  border-radius: 4px;
}

.stat-trend.positive {
  background: #d1fae5;
  color: #065f46;
}

.stat-trend.negative {
  background: #fee2e2;
  color: #dc2626;
}

.stat-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--color-text-main);
}

/* --- MAIN GRID (Charts & Activity) --- */
.main-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* Chart Placeholder */
.period-select {
  padding: 0.4rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  background: var(--color-bg-light);
  font-size: 0.875rem;
}

.chart-bars-container {
  height: 250px;
  display: flex;
  align-items: flex-end;
  gap: 1.2rem;
  padding-bottom: 0.5rem;
}

.chart-bar {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  height: 100%;
}

.bar-fill {
  background-color: var(--color-accent);
  width: 100%;
  border-radius: 4px 4px 0 0;
  min-height: 20px;
  transition: height 0.3s ease;
  opacity: 0.8;
}

.bar-fill:hover {
  opacity: 1;
}

.bar-label {
  margin-top: 0.5rem;
  font-size: 0.75rem;
  color: var(--color-text-muted);
}

/* Activity List */
.activity-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  border-radius: 6px;
  transition: background-color 0.2s;
  border-bottom: 1px dashed #f3f4f6;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
  background: var(--color-bg-light);
}

.activity-icon-box {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.activity-icon-box svg {
    color: var(--color-white) !important;
}

.activity-icon-box.accent { background: #3B82F6; }
.activity-icon-box.success { background: var(--color-primary); }
.activity-icon-box.danger { background: var(--color-danger); }
.activity-icon-box.warning { background: var(--color-warning); }


.activity-content {
  flex: 1;
  font-size: 0.875rem;
}

.activity-content p {
  margin: 0;
  line-height: 1.3;
  color: var(--color-text-main);
}

.activity-time {
  font-size: 0.75rem;
  color: var(--color-text-muted);
}

/* --- QUICK ACTIONS --- */
.quick-actions-section {
  margin-top: 1.5rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 1rem;
}

.quick-action-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 1.5rem 1rem;
  background: var(--color-bg-light);
  border: 1px solid #d1d5db;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.quick-action-btn:hover {
  background: var(--color-white);
  border-color: var(--color-accent);
  box-shadow: 0 4px 8px rgba(79, 70, 229, 0.1);
  transform: translateY(-2px);
}

.quick-action-btn svg {
  color: var(--color-accent);
}

.action-text {
  font-weight: 500;
  color: var(--color-text-main);
  font-size: 0.875rem;
}

/* --- RESPONSIVE --- */
@media (max-width: 1024px) {
  .main-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .welcome-section {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  .welcome-actions {
    width: 100%;
  }

  .stat-card {
    padding-top: 2rem;
  }
  .stat-icon-box {
    top: 1rem;
    right: 1rem;
  }
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(100%, 1fr));
  }
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>