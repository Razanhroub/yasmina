<template>
  <div class="create-student-form">
    <form @submit.prevent="submitForm">
      <h3>Create New Student</h3>

      <div class="form-group">
        <label>Name</label>
        <input type="text" v-model="form.name" placeholder="Enter student name" required />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" v-model="form.email" placeholder="Enter student email" required />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" v-model="form.password" placeholder="Enter password" required />
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" v-model="form.password_confirmation" placeholder="Confirm password" required />
      </div>

      <div class="form-group">
        <label>Birth Date</label>
        <input type="date" v-model="form.birth_of_date" />
      </div>

      <div class="form-group">
        <label>Country</label>
        <input type="text" v-model="form.country" placeholder="Enter country" />
      </div>

      <button type="submit" class="submit-btn">Create Student</button>
    </form>
  </div>
</template>

<script>
import api from '../../../axios';
import Swal from 'sweetalert2';

export default {
  name: 'CreateStudent',
  props: {
    classId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        birth_of_date: '',
        country: '',
        class_id: this.classId
      }
    };
  },
  methods: {
    resetForm() {
      this.form = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        birth_of_date: '',
        country: '',
        class_id: this.classId
      };
    },
    async submitForm() {
      if (!this.form.name || !this.form.email || !this.form.password || !this.form.password_confirmation) {
        Swal.fire('Error', 'Name, Email, and Password are required', 'error');
        return;
      }
      if (this.form.password !== this.form.password_confirmation) {
        Swal.fire('Error', 'Passwords do not match', 'error');
        return;
      }

      try {
        await api.post('/teacher/students', this.form);
        Swal.fire('Success', 'Student created successfully!', 'success');
        this.resetForm();
        this.$emit('created'); // notify parent to refresh classrooms
      } catch (err) {
        Swal.fire('Error', err.response?.data?.message || 'Failed to create student', 'error');
      }
    }
  }
};
</script>
