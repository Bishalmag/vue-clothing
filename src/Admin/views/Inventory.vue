<template>
  <div class="inventory-container">
    <h2>📋 Product Inventory</h2>
    
    <div class="inventory-actions">
      <input 
        type="text" 
        v-model="searchTerm" 
        placeholder="Search by name or category..." 
        class="search-input"
      >
      <button @click="goToAddProduct" class="add-button">
        + Add New Product
      </button>
    </div>

    <div v-if="filteredProducts.length > 0" class="table-wrapper">
      <table class="product-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in filteredProducts" :key="product.id">
            <td>{{ product.id }}</td>
            <td class="product-name">{{ product.name }}</td>
            <td>{{ product.category }}</td>
            <td>${{ product.price.toFixed(2) }}</td>
            <td>
              <span :class="{'low-stock': product.stock < 10, 'in-stock': product.stock >= 10}">
                {{ product.stock }}
              </span>
            </td>
            <td>
              <button @click="editProduct(product.id)" class="action-btn edit-btn">
                Edit
              </button>
              <button @click="confirmDelete(product.id, product.name)" class="action-btn delete-btn">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="empty-state">
      <p v-if="products.length > 0">No products match your search term "{{ searchTerm }}".</p>
      <p v-else>Your inventory is currently empty.</p>
    </div>
    
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete **{{ productToDeleteName }}** (ID: {{ productToDeleteId }})?</p>
        <div class="modal-actions">
          <button @click="deleteProduct" class="delete-btn">Yes, Delete</button>
          <button @click="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// --- State Management ---

// Simulated list of products
const products = ref([
  { id: 101, name: 'Classic Crew Neck Tee', category: 'T-Shirts', price: 19.99, stock: 45 },
  { id: 102, name: 'Slim Fit Denim Jeans', category: 'Pants', price: 59.99, stock: 8 },
  { id: 103, name: 'Floral Summer Dress', category: 'Dresses', price: 85.00, stock: 22 },
  { id: 104, name: 'Leather Belt', category: 'Accessories', price: 25.50, stock: 15 },
  { id: 105, name: 'Wool Knit Sweater', category: 'Sweaters', price: 49.99, stock: 3 },
]);

const searchTerm = ref('');
const showDeleteModal = ref(false);
const productToDeleteId = ref(null);
const productToDeleteName = ref('');

// --- Computed Property for Filtering ---

const filteredProducts = computed(() => {
  const term = searchTerm.value.toLowerCase().trim();
  if (!term) {
    return products.value;
  }
  return products.value.filter(product => 
    product.name.toLowerCase().includes(term) ||
    product.category.toLowerCase().includes(term)
  );
});

// --- Methods ---

const goToAddProduct = () => {
  // In a real Vue Router setup: router.push('/admin/products/add');
  console.log('Navigating to Add Product Page...');
};

const editProduct = (id) => {
  // In a real Vue Router setup: router.push(`/admin/products/edit/${id}`);
  console.log(`Editing product with ID: ${id}`);
};

const confirmDelete = (id, name) => {
  productToDeleteId.value = id;
  productToDeleteName.value = name;
  showDeleteModal.value = true;
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  productToDeleteId.value = null;
  productToDeleteName.value = '';
};

const deleteProduct = () => {
  if (productToDeleteId.value) {
    console.log(`DELETING product ID: ${productToDeleteId.value}`);
    
    // In a real application, you would make an API call (e.g., axios.delete('/api/products/id'))
    
    // Update the local state (simulating successful deletion)
    products.value = products.value.filter(p => p.id !== productToDeleteId.value);
    
    cancelDelete(); // Close the modal
  }
};
</script>

<style scoped>
/* --- Layout and Structure --- */
.inventory-container {
  max-width: 1000px;
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

/* --- Actions Bar --- */
.inventory-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.search-input {
  padding: 10px;
  width: 300px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.add-button {
  padding: 10px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s;
}

.add-button:hover {
  background-color: #0056b3;
}

/* --- Table Styling --- */
.table-wrapper {
  overflow-x: auto;
}

.product-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.product-table th, .product-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
}

.product-table th {
  background-color: #f2f2f2;
  font-weight: 700;
  color: #555;
  text-transform: uppercase;
  font-size: 14px;
}

.product-table tbody tr:hover {
  background-color: #f9f9f9;
}

.product-name {
  font-weight: 600;
}

/* --- Stock Status --- */
.low-stock {
  color: white;
  background-color: #ffc107; /* Yellow for Low Stock */
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 700;
}

.in-stock {
  color: #28a745; /* Green for In Stock */
  font-weight: 600;
}

/* --- Action Buttons --- */
.action-btn {
  padding: 6px 10px;
  margin-right: 5px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: opacity 0.3s;
}

.action-btn:hover {
    opacity: 0.8;
}

.edit-btn {
  background-color: #17a2b8; /* Info Blue */
  color: white;
}

.delete-btn {
  background-color: #dc3545; /* Danger Red */
  color: white;
}

/* --- Empty State --- */
.empty-state {
  text-align: center;
  padding: 50px;
  border: 1px dashed #ccc;
  border-radius: 8px;
  margin-top: 20px;
}

/* --- Modal Styling --- */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  text-align: center;
  max-width: 400px;
}

.modal-actions {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  gap: 15px;
}

.cancel-btn {
  padding: 10px 20px;
  background-color: #6c757d; /* Secondary Gray */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
</style>