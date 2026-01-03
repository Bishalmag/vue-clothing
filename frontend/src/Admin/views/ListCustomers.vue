<template>
  <div class="customer-container">
    <h2>👤 User Management</h2>

    <p v-if="loading" class="loading">Loading users...</p>
    <p v-if="error" class="error">{{ error }}</p>

    <div v-if="!loading && users.length" class="table-wrapper">
      <table class="customer-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Joined Date</th>
            <th width="120">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.first_name }} {{ user.last_name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ formatDate(user.created_at) }}</td>
            <td>
              <button class="action-btn delete-btn" :disabled="deletingId === user.id" @click="deleteUser(user.id)">
                {{ deletingId === user.id ? 'Deleting...' : 'Delete' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="!loading && !users.length" class="empty-state">
      <p>No users found.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import adminApi from '@/api/adminaxois'

const users = ref([])
const loading = ref(false)
const error = ref('')
const deletingId = ref(null)

async function fetchUsers() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminApi.get('/users')
    users.value = Array.isArray(res.data) ? res.data : (res.data?.data ?? [])
  } catch (err) {
    console.error(err)
    error.value = err.response?.data?.message || err.message || 'Failed to load users'
  } finally { loading.value = false }
}

function formatDate(date) {
  return date ? new Date(date).toISOString().split('T')[0] : '—'
}

async function deleteUser(id) {
  if (!window.confirm('Are you sure you want to delete this user?')) return
  deletingId.value = id
  try {
    await adminApi.delete(`/users/${id}`)
    users.value = users.value.filter(u => u.id !== id)
  } catch (err) {
    console.error(err)
    window.alert(err.response?.data?.message || 'Failed to delete user')
  } finally { deletingId.value = null }
}

onMounted(fetchUsers)
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
  margin-bottom: 25px;
}

.table-wrapper {
  overflow-x: auto;
}

.customer-table {
  width: 100%;
  border-collapse: collapse;
}

.customer-table th,
.customer-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
}

.customer-table th {
  background: #f2f2f2;
}

.action-btn {
  padding: 6px 10px;
  border: none;
  border-radius: 4px;
  color: #fff;
  cursor: pointer;
  margin-right: 6px;
  font-size: 14px;
}

.delete-btn {
  background: #dc3545;
}

.delete-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.loading {
  text-align: center;
  font-weight: bold;
}

.error {
  text-align: center;
  color: red;
}

.empty-state {
  text-align: center;
  padding: 40px;
  border: 1px dashed #ccc;
  border-radius: 8px;
}
</style>

