<template>
  <div class="product-add-container">
    <h2>➕ Add New Clothing Product</h2>
    <form @submit.prevent="submitProduct" class="product-form">
      
      <div class="form-group">
        <label for="productName">Product Name:</label>
        <input 
          type="text" 
          id="productName" 
          v-model="product.name" 
          required
        >
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea 
          id="description" 
          v-model="product.description" 
          rows="4" 
          required
        ></textarea>
      </div>

      <div class="form-group-row">
        <div class="form-group">
          <label for="price">Price ($):</label>
          <input 
            type="number" 
            id="price" 
            v-model.number="product.price" 
            min="0.01" 
            step="0.01" 
            required
          >
        </div>
        <div class="form-group">
          <label for="stock">Initial Stock:</label>
          <input 
            type="number" 
            id="stock" 
            v-model.number="product.stock" 
            min="0" 
            required
          >
        </div>
      </div>

      <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" v-model="product.category" required>
          <option value="" disabled>Select a category</option>
          <option value="T-Shirts">T-Shirts</option>
          <option value="Pants">Pants</option>
          <option value="Dresses">Dresses</option>
          <option value="Accessories">Accessories</option>
        </select>
      </div>

      <div class="form-group">
        <label>Available Sizes (e.g., S, M, L):</label>
        <input 
          type="text" 
          v-model="product.sizes" 
          placeholder="Comma separated: S, M, L, XL"
        >
      </div>
       <div class="form-group">
        <label>Available Colors:</label>
        <input 
          type="text" 
          v-model="product.colors" 
          placeholder="Comma separated: Red, Blue, Black"
        >
      </div>
      
      <div class="form-group">
        <label for="image">Product Image:</label>
        <input 
          type="file" 
          id="image" 
          @change="handleImageUpload" 
          accept="image/*"
        >
      </div>

      <button type="submit" class="submit-button">Create Product</button>
      
      <p v-if="submissionStatus" :class="submissionStatus.type">{{ submissionStatus.message }}</p>

    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';

// Use reactive for the product state object
const product = reactive({
  name: '',
  description: '',
  price: 0.01,
  stock: 1,
  category: '',
  sizes: '', // Storing as a comma-separated string for simplicity
  colors: '', // Storing as a comma-separated string for simplicity
  imageFile: null,
});

// Use ref for submission feedback
const submissionStatus = ref(null);

/**
 * Handles the file input change event to store the selected file.
 * @param {Event} event 
 */
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    product.imageFile = file;
  }
};

/**
 * Handles the form submission logic.
 */
const submitProduct = () => {
  // In a real application, you would perform data validation here
  
  console.log('--- Product Data Submitted ---');
  console.log('Product Object:', product);
  
  // 1. Prepare Data for API:
  // For a real API call, you would typically use 'FormData' for file uploads.
  // Example of converting sizes/colors to arrays:
  const sizesArray = product.sizes.split(',').map(s => s.trim()).filter(s => s.length > 0);
  const colorsArray = product.colors.split(',').map(c => c.trim()).filter(c => c.length > 0);

  const payload = {
    name: product.name,
    description: product.description,
    price: product.price,
    stock: product.stock,
    category: product.category,
    sizes: sizesArray,
    colors: colorsArray,
    // Note: The 'imageFile' object is what you'd send to the backend service.
    // imageFile: product.imageFile,
  };

  console.log('API Payload (excluding file):', payload);
  
  // 2. Simulate an API POST Request:
  // axios.post('/api/products/add', formData)
  
  // 3. Provide User Feedback:
  submissionStatus.value = {
    message: `Product "${product.name}" successfully prepared for submission! (Check console for data)`,
    type: 'success'
  };

  // 4. Optionally Reset Form:
  // product.name = ''; 
  // ... reset all fields 
};
</script>

<style scoped>
/* Basic styling for an admin page form */
.product-add-container {
  max-width: 600px;
  margin: 40px auto;
  padding: 30px;
  background: #f8f9fa;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
  border-bottom: 2px solid #ddd;
  padding-bottom: 15px;
}

.product-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group-row {
  display: flex;
  gap: 20px;
}

.form-group-row > .form-group {
  flex: 1; /* Makes the price and stock fields share the row space */
}

label {
  font-weight: 600;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"], 
input[type="number"], 
select, 
textarea {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  transition: border-color 0.3s;
  width: 100%;
  box-sizing: border-box; /* Important for padding/border within width */
}

input:focus, 
select:focus, 
textarea:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
}

textarea {
  resize: vertical;
}

.submit-button {
  padding: 12px 20px;
  background-color: #28a745; /* Success Green */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 17px;
  font-weight: 700;
  margin-top: 10px;
  transition: background-color 0.3s;
}

.submit-button:hover {
  background-color: #218838;
}

.success {
  color: #155724;
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  padding: 10px;
  border-radius: 5px;
  margin-top: 15px;
  text-align: center;
}
</style>