<template>
  <div class="customer-container">
    <h2>👤 Customer Management</h2>

    <div class="list-actions">
      <input 
        type="text" 
        v-model="searchTerm" 
        placeholder="Search by name or email..." 
        class="search-input"
      >
      <select v-model="filterStatus" class="filter-select">
        <option value="">All Statuses</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
        <option value="VIP">VIP</option>
      </select>
    </div>

    <div v-if="filteredCustomers.length > 0" class="table-wrapper">
      <table class="customer-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in filteredCustomers" :key="customer.id">
            <td>{{ customer.id }}</td>
            <td>{{ customer.name }}</td>
            <td>{{ customer.email }}</td>
            <td>{{ customer.joinedDate }}</td>
            <td>
              <span :class="['status-badge', customer.status.toLowerCase()]">
                {{ customer.status }}
              </span>
            </td>
            <td>
              <button @click="viewCustomer(customer.id)" class="action-btn view-btn">View</button>
              <button @click="toggleStatus(customer.id, customer.status)" class="action-btn toggle-btn">
                {{ customer.status === 'Active' ? 'Deactivate' : 'Activate' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-state">
      <p>No customers match your current filters.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const customers = ref([
  { id: 1, name: 'Alice Johnson', email: 'alice@example.com', joinedDate: '2023-10-01', status: 'Active' },
  { id: 2, name: 'Bob Smith', email: 'bob@example.com', joinedDate: '2023-08-15', status: 'Inactive' },
  { id: 3, name: 'Charlie Brown', email: 'charlie@example.com', joinedDate: '2024-01-20', status: 'VIP' },
  { id: 4, name: 'Diana Prince', email: 'diana@example.com', joinedDate: '2023-11-25', status: 'Active' },
]);

const searchTerm = ref('');
const filterStatus = ref('');

const filteredCustomers = computed(() => {
  const term = searchTerm.value.toLowerCase().trim();
  const status = filterStatus.value;

  return customers.value.filter(customer => {
    const matchesSearch = customer.name.toLowerCase().includes(term) || 
                          customer.email.toLowerCase().includes(term);
    const matchesStatus = !status || customer.status === status;
    
    return matchesSearch && matchesStatus;
  });
});

const viewCustomer = (id) => {
  console.log(`Viewing customer profile for ID: ${id}`);
};

const toggleStatus = (id, currentStatus) => {
  const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
  const customerIndex = customers.value.findIndex(c => c.id === id);
  
  if (customerIndex !== -1) {
    customers.value[customerIndex].status = newStatus;
    console.log(`Customer ID ${id} status changed to ${newStatus}`);
  }
};
</script>

<style scoped>
.customer-container {
  max-width: 900px;
  margin: 40px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
}

.list-actions {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.search-input, .filter-select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.search-input {
  flex-grow: 1;
}

/* --- Table Styling (reusing Inventory styles for consistency) --- */
.table-wrapper { overflow-x: auto; }
.customer-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}
.customer-table th, .customer-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
}
.customer-table th {
  background-color: #f2f2f2;
  font-weight: 700;
  color: #555;
}

/* --- Status Badges --- */
.status-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 600;
  font-size: 13px;
}
.active { background-color: #d4edda; color: #155724; } /* Green */
.inactive { background-color: #f8d7da; color: #721c24; } /* Red */
.vip { background-color: #fff3cd; color: #856404; } /* Yellow/Gold */

/* --- Action Buttons --- */
.action-btn {
  padding: 6px 10px;
  margin-right: 5px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  color: white;
}
.view-btn { background-color: #17a2b8; }
.toggle-btn { background-color: #fd7e14; } /* Orange */

.empty-state {
  text-align: center;
  padding: 50px;
  border: 1px dashed #ccc;
  border-radius: 8px;
  margin-top: 20px;
}
</style>