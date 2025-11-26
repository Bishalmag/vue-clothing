<template>
  <div class="settings-container">
    <h2>⚙️ Administration Settings</h2>
    <form @submit.prevent="saveSettings" class="settings-form">
      
      <section class="settings-section">
        <h3>General Configuration</h3>
        
        <div class="form-group">
          <label for="storeName">Store Name:</label>
          <input type="text" id="storeName" v-model="settings.general.storeName" required>
        </div>
        
        <div class="form-group">
          <label for="currency">Default Currency:</label>
          <select id="currency" v-model="settings.general.currency" required>
            <option value="USD">USD ($)</option>
            <option value="EUR">EUR (€)</option>
            <option value="GBP">GBP (£)</option>
          </select>
        </div>
      </section>

      <section class="settings-section">
        <h3>Inventory Alerts</h3>
        
        <div class="form-group">
          <label for="lowStockThreshold">Low Stock Threshold:</label>
          <input 
            type="number" 
            id="lowStockThreshold" 
            v-model.number="settings.inventory.lowStockThreshold" 
            min="1"
            required
          >
          <p class="help-text">Items below this count will show a low stock warning.</p>
        </div>

        <div class="form-group toggle-group">
          <label for="enableEmailAlerts">Enable Email Alerts:</label>
          <input type="checkbox" id="enableEmailAlerts" v-model="settings.inventory.enableEmailAlerts">
        </div>
      </section>

      <section class="settings-section">
        <h3>Security</h3>
        
        <div class="form-group">
          <label for="sessionTimeout">Session Timeout (minutes):</label>
          <input 
            type="number" 
            id="sessionTimeout" 
            v-model.number="settings.security.sessionTimeoutMinutes" 
            min="5"
            required
          >
        </div>
      </section>
      
      <button type="submit" class="save-button">Save All Settings</button>
      
      <p v-if="saveMessage" :class="saveStatus">{{ saveMessage }}</p>

    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';

// Using reactive for the complex nested settings object
const settings = reactive({
  general: {
    storeName: 'The Clothing Boutique',
    currency: 'USD',
  },
  inventory: {
    lowStockThreshold: 10,
    enableEmailAlerts: true,
  },
  security: {
    sessionTimeoutMinutes: 60,
  }
});

const saveMessage = ref(null);
const saveStatus = ref('');

const saveSettings = () => {
  console.log('--- Settings Saved ---');
  console.log(settings);

  // 1. Simulate API call to save settings
  // axios.post('/api/admin/settings', settings)
  
  // 2. Provide feedback
  saveMessage.value = 'Settings updated successfully!';
  saveStatus.value = 'success-message';
  
  // Clear message after a few seconds
  setTimeout(() => {
    saveMessage.value = null;
  }, 3000);
};
</script>

<style scoped>
.settings-container {
  max-width: 700px;
  margin: 40px auto;
  padding: 30px;
  background: #f8f9fa;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 30px;
}

.settings-form {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.settings-section {
  background: white;
  padding: 20px;
  border-radius: 8px;
  border-left: 5px solid #007bff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.settings-section h3 {
  color: #007bff;
  margin-top: 0;
  margin-bottom: 15px;
  padding-bottom: 5px;
  border-bottom: 1px dashed #eee;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"], 
input[type="number"], 
select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.help-text {
  font-size: 12px;
  color: #6c757d;
  margin-top: 5px;
}

/* Toggle specific styling */
.toggle-group {
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #eee;
  padding-top: 15px;
}

.toggle-group input[type="checkbox"] {
  width: 20px;
  height: 20px;
  margin-left: 10px;
}

.save-button {
  padding: 12px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 17px;
  font-weight: 700;
  transition: background-color 0.3s;
}

.save-button:hover {
  background-color: #218838;
}

.success-message {
  color: #155724;
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  padding: 10px;
  border-radius: 5px;
  margin-top: 15px;
  text-align: center;
}
</style>