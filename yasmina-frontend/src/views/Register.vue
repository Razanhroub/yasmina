<template>
  <div class="register-container">
    <h2 class="title">Register</h2>
    <form @submit.prevent="register" class="register-form">
      
      <!-- Name Field -->
      <div class="form-group">
        <label>Name:</label>
        <input v-model="name" type="text" placeholder="Enter your name" required />
        <p v-if="errors.name" class="error-msg">{{ errors.name[0] }}</p>
      </div>
      
      <!-- Email Field -->
      <div class="form-group">
        <label>Email:</label>
        <input v-model="email" type="email" placeholder="Enter your email" required />
        <p v-if="errors.email" class="error-msg">{{ errors.email[0] }}</p>
      </div>
      
      <!-- Password Field -->
      <div class="form-group">
        <label>Password:</label>
        <input v-model="password" type="password" placeholder="Enter your password" required />
        <p v-if="errors.password" class="error-msg">{{ errors.password[0] }}</p>
      </div>
      
      <!-- Confirm Password Field -->
      <div class="form-group">
        <label>Confirm Password:</label>
        <input v-model="password_confirmation" type="password" placeholder="Confirm your password" required />
        <p v-if="errors.password_confirmation" class="error-msg">{{ errors.password_confirmation[0] }}</p>
      </div>
      
      <button type="submit" class="btn">Register</button>
    </form>

    <!-- Success / Error Message -->
    <p v-if="message" :class="{'message': success, 'error-msg': !success}">{{ message }}</p>
     <p class="login-link">
      Already have an account? 
      <button @click="$router.push('/login')" class="btn-link">Login</button>
    </p>
  </div>
</template>

<script>
import api from '../axios';
import router from '../router';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      message: '',
      success: false,
      errors: {} // store validation errors here
    };
  },
  methods: {
    async register() {
      // Reset errors and messages
      this.errors = {};
      this.message = '';
      this.success = false;

      try {
        // Send registration request
        const res = await api.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        // Store token and user info in localStorage (auto-login)
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('user', JSON.stringify({
          id: res.data.id,
          name: res.data.name,
          email: res.data.email,
          role: res.data.role
        }));

        // Show success message
        this.message = res.data.message;
        this.success = true;

        // Redirect directly to dashboard
        await router.push('/dashboard');

      } catch (err) {
        // Laravel validation errors
        if (err.response?.data?.errors) {
          this.errors = err.response.data.errors;
        } else {
          this.message = err.response?.data?.message || 'Registration failed';
          this.success = false;
        }
      }
    }
  }
};
</script>
