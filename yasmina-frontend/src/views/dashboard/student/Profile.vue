<template>
  <div>
    <h2>Student Profile</h2>

    <!-- Classroom Button & Component (above form) -->
    <div style="margin-bottom: 20px;">
      <button type="button" @click="toggleClassroom">
        {{ showClassroom ? 'Hide Class' : 'View Class' }}
      </button>

      <div v-if="showClassroom" style="margin-top: 10px; padding: 10px; border: 1px solid #ccc;">
        <div v-if="classroom">
          <h3>Class Name: {{ classroom.name }}</h3>
          <p>Description: {{ classroom.description }}</p>
          <p>Teacher: {{ classroom.teacher ? classroom.teacher.name : 'No teacher assigned' }}</p>
        </div>
        <div v-else>
          <p>No class assigned.</p>
        </div>
      </div>
    </div>

    <!-- Profile Form -->
    <form @submit.prevent="submitUpdate">
      <!-- Name -->
      <div>
        <label>Name:</label>
        <input type="text" v-model="form.name" :readonly="!isEditing" />
        <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>
      </div>

      <!-- Email -->
      <div>
        <label>Email:</label>
        <input type="email" v-model="form.email" :readonly="!isEditing" />
        <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
      </div>

      <!-- Birth Date -->
      <div>
        <label>Birth Date:</label>
        <input type="date" v-model="form.birth_of_date" :readonly="!isEditing" />
        <span v-if="errors.birth_of_date" class="error">{{ errors.birth_of_date[0] }}</span>
      </div>

      <!-- Country -->
      <div>
        <label>Country:</label>
        <input type="text" v-model="form.country" :readonly="!isEditing" />
        <span v-if="errors.country" class="error">{{ errors.country[0] }}</span>
      </div>

      <!-- Password -->
      <div v-if="isEditing">
        <label>Password (leave blank if no change):</label>
        <input type="password" v-model="form.password" />
        <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
      </div>

      <div v-if="isEditing">
        <label>Confirm Password:</label>
        <input type="password" v-model="form.password_confirmation" />
      </div>

      <div style="margin-top: 15px;">
        <button type="button" v-if="!isEditing" @click="toggleEdit">Edit Profile</button>
        <button type="submit" v-if="isEditing">Update Profile</button>
        <button type="button" v-if="isEditing" @click="cancelEdit">Cancel</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const isEditing = ref(false);
const showClassroom = ref(false);

const form = ref({
  name: '',
  email: '',
  birth_of_date: '',
  country: '',
  password: '',
  password_confirmation: ''
});

const classroom = ref(null);
const errors = ref({});
let originalForm = {};

// Load profile on mount
onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/student/profile', {
      headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
    });

    const profile = response.data.data;

    form.value.name = profile.user.name;
    form.value.email = profile.user.email;
    form.value.birth_of_date = profile.birth_of_date ? profile.birth_of_date.split('T')[0] : '';
    form.value.country = profile.country || '';
    originalForm = { ...form.value };

    classroom.value = profile.classroom || null;

  } catch (err) {
    console.error(err);
    Swal.fire('Error', 'Failed to load profile', 'error');
  }
});

const toggleEdit = () => { isEditing.value = true; };
const cancelEdit = () => {
  isEditing.value = false;
  form.value = { ...originalForm, password: '', password_confirmation: '' };
  errors.value = {};
};
const toggleClassroom = () => { showClassroom.value = !showClassroom.value; };

const submitUpdate = async () => {
  errors.value = {}; // reset errors

  const result = await Swal.fire({
    title: 'Update Profile?',
    text: "Are you sure you want to update your profile?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, update',
    cancelButtonText: 'Cancel'
  });

  if (!result.isConfirmed) return;

  try {
    // Only include changed fields
    const payload = {};

    if (form.value.name !== originalForm.name) payload.name = form.value.name;
    if (form.value.email !== originalForm.email) payload.email = form.value.email;
    if (form.value.birth_of_date !== originalForm.birth_of_date) payload.birth_of_date = form.value.birth_of_date;
    if (form.value.country !== originalForm.country) payload.country = form.value.country;
    if (form.value.password.trim() !== '') {
      payload.password = form.value.password;
      payload.password_confirmation = form.value.password_confirmation;
    }

    if (Object.keys(payload).length === 0) {
      Swal.fire('No Changes', 'No fields have been changed.', 'info');
      return;
    }

    const response = await axios.put('http://localhost:8000/api/student/profile', payload, {
      headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
    });

    Swal.fire('Success', response.data.message, 'success');
    originalForm = { ...form.value };
    isEditing.value = false;
    form.value.password = '';
    form.value.password_confirmation = '';

  } catch (err) {
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors; // show validation errors
    } else {
      console.error(err);
      Swal.fire('Error', err.response?.data?.message || 'Update failed', 'error');
    }
  }
};
</script>

<style scoped>
form div { margin-bottom: 10px; }
label { display: block; margin-bottom: 4px; }
input[readonly] { background-color: #f5f5f5; border: 1px solid #ccc; }
input { padding: 6px; width: 100%; box-sizing: border-box; }
button { padding: 8px 16px; margin-right: 10px; cursor: pointer; }
.error { color: red; font-size: 0.85em; margin-top: 2px; display: block; }
</style>
