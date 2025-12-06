<template>
  <div class="create-student">
    <button @click="toggleForm" class="toggle-btn">
      {{ showForm ? 'Cancel' : 'Create Student' }}
    </button>

    <form v-if="showForm" @submit.prevent="submitForm" class="student-form">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" v-model="form.name" id="name" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" v-model="form.email" id="email" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" v-model="form.password" id="password" required />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" v-model="form.password_confirmation" id="password_confirmation" required />
      </div>

      <div class="form-group">
        <label for="birth_of_date">Birth Date</label>
        <input type="date" v-model="form.birth_of_date" id="birth_of_date" />
      </div>

      <div class="form-group">
        <label for="country">Country</label>
        <input type="text" v-model="form.country" id="country" />
      </div>

      <div class="form-group">
        <label for="class_id">Assign Classroom</label>
        <select v-model="form.class_id" id="class_id">
          <option value="">Select Classroom</option>
          <option v-for="classroom in classrooms" :key="classroom.id" :value="classroom.id">
            {{ classroom.name }}
          </option>
        </select>
      </div>

      <button type="submit" class="submit-btn">Create</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import Swal from 'sweetalert2';
import api from '../../../../axios.js';

const emit = defineEmits(['created']);

const showForm = ref(false);
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  birth_of_date: '',
  country: '',
  class_id: ''
});
const classrooms = ref([]);

const toggleForm = () => {
  showForm.value = !showForm.value;
};

// Fetch classrooms from backend
const fetchClassrooms = async () => {
  try {
    const res = await api.get('/admin/classrooms');
    classrooms.value = res.data;
  } catch (err) {
    console.error('Failed to fetch classrooms:', err);
  }
};

// Submit form to backend
const submitForm = async () => {
  if (!form.value.name || !form.value.email || !form.value.password || !form.value.password_confirmation) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Name, Email, and Password are required'
    });
    return;
  }

  if (form.value.password !== form.value.password_confirmation) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Passwords do not match'
    });
    return;
  }

  try {
    const res = await api.post('/admin/students', form.value);

    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Student created successfully!',
      timer: 2000,
      showConfirmButton: false
    });

    emit('created', res.data);

    // Reset form
    form.value = { name: '', email: '', password: '', password_confirmation: '', birth_of_date: '', country: '', class_id: '' };
    showForm.value = false;
  } catch (err) {
    console.error('Failed to create student:', err.response || err);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to create student'
    });
  }
};

onMounted(() => {
  fetchClassrooms();
});
</script>

<style scoped>
.create-student {
  margin-bottom: 20px;
}

.toggle-btn {
  padding: 6px 12px;
  margin-bottom: 10px;
  cursor: pointer;
}

.student-form {
  border: 1px solid #ccc;
  padding: 15px;
  border-radius: 5px;
  background: #f9f9f9;
}

.form-group {
  margin-bottom: 10px;
}

.form-group label {
  display: block;
  margin-bottom: 4px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 6px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.submit-btn {
  padding: 6px 12px;
  cursor: pointer;
}
</style>
