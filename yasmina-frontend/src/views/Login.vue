<template>
  <div class="register-container">
    <h2 class="title">Login</h2>
    <form @submit.prevent="login" class="register-form">

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

      <button type="submit" class="btn">Login</button>
    </form>
    <!-- Don't have account link -->
    <p class="login-link">
      Don’t have an account? 
      <button @click="$router.push('/register')" class="btn-link">Register</button>
    </p>
    <!-- Success / Error Message -->
    <p v-if="message" :class="{'message': success, 'error-msg': !success}">{{ message }}</p>
  </div>
</template>

<script>
import api from '../axios';
import router from '../router';

export default {
  data() {
    return {
      email: '',
      password: '',
      message: '',
      success: false,
      errors: {} // store validation errors
    };
  },
  methods: {
    async login() {
      // Reset errors and messages
      this.errors = {};
      this.message = '';
      this.success = false;

      try {
        // Make login request
        const res = await api.post('/login', {
          email: this.email,
          password: this.password
        });

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

        // Redirect to dashboard after login
        router.push('/dashboard');

      } catch (err) {
        if (err.response?.data?.errors) {
          // Validation errors from Laravel
          this.errors = err.response.data.errors;
        } else {
          // General error (invalid credentials)
          this.message = err.response?.data?.message || 'Login failed';
          this.success = false;
        }
      }
    }
  }
};
</script>