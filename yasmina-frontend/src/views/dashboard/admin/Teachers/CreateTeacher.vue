<template>
  <div class="create-teacher">
    <button @click="toggleForm" class="toggle-btn">
      {{ showForm ? 'Cancel' : 'Create Teacher' }}
    </button>

    <form v-if="showForm" @submit.prevent="submitForm" class="teacher-form">
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
        <label for="class_names">Assign Classrooms (comma-separated names)</label>
        <input type="text" v-model="form.class_names" id="class_names" placeholder="e.g. Math 101, Science 202" />
      </div>

      <button type="submit" class="submit-btn">Create</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Swal from 'sweetalert2';
import api from '../../../../axios.js';
import { defineEmits } from 'vue';

const emit = defineEmits(['created']);

const showForm = ref(false);
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  class_names: '', // comma-separated class names
});

const toggleForm = () => showForm.value = !showForm.value;

const submitForm = async () => {
  if (!form.value.name || !form.value.email || !form.value.password || !form.value.password_confirmation) return;
  if (form.value.password !== form.value.password_confirmation) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match' });
    return;
  }

  // convert comma-separated names into array
  const classNamesArray = form.value.class_names
    .split(',')
    .map(name => name.trim())
    .filter(name => name.length > 0);

  try {
    const payload = { 
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      class_names: classNamesArray
    };

    const res = await api.post('/admin/teachers', payload);
    Swal.fire({ icon: 'success', title: 'Success', text: 'Teacher created!', timer: 2000, showConfirmButton: false });
    emit('created', res.data);

    form.value = { name: '', email: '', password: '', password_confirmation: '', class_names: '' };
    showForm.value = false;
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: err.response?.data?.message || 'Failed to create teacher' });
    console.error(err);
  }
};
</script>

<style scoped>
.create-teacher { margin-bottom: 20px; }
.toggle-btn { padding: 6px 12px; margin-bottom: 10px; cursor: pointer; }
.teacher-form { border: 1px solid #ccc; padding: 15px; border-radius: 5px; background: #f9f9f9; }
.form-group { margin-bottom: 10px; }
.form-group label { display: block; margin-bottom: 4px; }
.form-group input { width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
.submit-btn { padding: 6px 12px; cursor: pointer; }
</style>
