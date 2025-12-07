<template>
  <div class="create-classroom">
    <button @click="toggleForm" class="toggle-btn">
      {{ showForm ? 'Cancel' : 'Create Classroom' }}
    </button>

    <form v-if="showForm" @submit.prevent="submitForm" class="classroom-form">
      <div class="form-group">
        <label for="name">Classroom Name</label>
        <input type="text" v-model="form.name" id="name" required />
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea v-model="form.description" id="description"></textarea>
      </div>

      <div class="form-group">
        <label for="teacher_id">Assign Teacher</label>
        <select v-model="form.teacher_id" id="teacher_id">
          <option value="">Select Teacher</option>
          <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
            {{ teacher.name }}
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
  description: '',
  teacher_id: ''
});
const teachers = ref([]);

const toggleForm = () => {
  showForm.value = !showForm.value;
};

// Fetch teachers from backend
const fetchTeachers = async () => {
  try {
    const res = await api.get('/admin/teachers');
    teachers.value = res.data;
  } catch (err) {
    console.error('Failed to fetch teachers:', err);
  }
};

// Submit form to backend
const submitForm = async () => {
  if (!form.value.name) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Classroom name is required'
    });
    return;
  }

  try {
    const res = await api.post('/admin/classrooms', form.value);

    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Classroom created successfully!',
      timer: 2000,
      showConfirmButton: false
    });

    // Emit event to refresh parent table
    emit('created', res.data);

    // Reset form
    form.value = { name: '', description: '', teacher_id: '' };
    showForm.value = false;
  } catch (err) {
    console.error('Failed to create classroom:', err.response || err);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to create classroom'
    });
  }
};

onMounted(() => {
  fetchTeachers();
});
</script>


