<template>
  <div class="profile">
    <!-- Header -->
    <div class="profile-header">
      <div class="profile-info">
        <img 
          :src="user.avatar || '/default-avatar.png'" 
          :alt="user.name"
          class="avatar"
        >
        <div>
          <h2>{{ user.name }}</h2>
          <p>{{ user.email }}</p>
          <span class="member-since">Joined {{ formatDate(user.joinDate) }}</span>
        </div>
      </div>
      <button @click="toggleEdit" class="btn-edit">
        {{ editMode ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <!-- Edit Form -->
    <form v-if="editMode" @submit.prevent="saveProfile" class="edit-form">
      <div class="form-group">
        <label>Name</label>
        <input v-model="editForm.name" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" v-model="editForm.email" required>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="tel" v-model="editForm.phone">
      </div>
      <button type="submit" class="btn-save">Save</button>
    </form>

    <!-- Stats -->
    <div class="stats">
      <div class="stat">
        <strong>{{ user.stats.orders }}</strong>
        <span>Orders</span>
      </div>
      <div class="stat">
        <strong>{{ user.stats.wishlist }}</strong>
        <span>Wishlist</span>
      </div>
      <div class="stat">
        <strong>{{ user.stats.reviews }}</strong>
        <span>Reviews</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserProfile',
  data() {
    return {
      editMode: false,
      user: {
        name: 'Bishal Gharti',
        email: 'ghartimagarbishal87@gmail.com',
        avatar: '/avatars/user-1.jpg',
        phone: '9828384009',
        joinDate: '2023-01-15',
        stats: { orders: 12, wishlist: 8, reviews: 5 }
      },
      editForm: {}
    }
  },
  methods: {
    toggleEdit() {
      this.editMode = !this.editMode
      if (this.editMode) {
        this.editForm = { ...this.user }
      }
    },
    
    saveProfile() {
      Object.assign(this.user, this.editForm)
      this.editMode = false
      this.$emit('profile-updated', this.user)
    },
    
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long'
      })
    }
  }
}
</script>

<style scoped>
.profile {
  max-width: 600px;
  margin: 0 auto;
  padding: 2rem;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 2rem;
  gap: 1rem;
}

.profile-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}

.profile-info h2 {
  margin: 0 0 0.25rem 0;
  font-size: 1.5rem;
}

.profile-info p {
  margin: 0 0 0.5rem 0;
  color: #666;
}

.member-since {
  font-size: 0.875rem;
  color: #888;
}

.btn-edit {
  background: #000;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.btn-edit:hover {
  background: #333;
}

.edit-form {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #000;
}

.btn-save {
  background: #000;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.btn-save:hover {
  background: #333;
}

.stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-top: 2rem;
}

.stat {
  text-align: center;
  padding: 1.5rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.stat strong {
  display: block;
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.stat span {
  color: #666;
}

@media (max-width: 768px) {
  .profile {
    padding: 1rem;
  }
  
  .profile-header {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .profile-info {
    flex-direction: column;
  }
  
  .stats {
    grid-template-columns: 1fr;
  }
}
</style>