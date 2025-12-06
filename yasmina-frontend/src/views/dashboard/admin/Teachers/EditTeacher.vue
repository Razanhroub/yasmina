<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <h3>Edit Teacher</h3>

      <form @submit.prevent="submitForm">
        <!-- Name -->
        <div class="form-group">
          <label>Name</label>
          <input type="text" v-model="form.name" required />
        </div>

        <!-- Email -->
        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="form.email" required />
        </div>

        <!-- Password -->
        <div class="form-group">
          <label>Password (leave blank if no change)</label>
          <input type="password" v-model="form.password" />
        </div>

        <!-- Password confirmation -->
        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" v-model="form.password_confirmation" />
        </div>

        <!-- Classroom selection -->
        <div class="form-group">
          <label>Assign Classroom</label>
          <select v-model="form.class_id">
            <option value="">N/A</option>
            <option v-for="c in classrooms" :key="c.id" :value="c.id">
              {{ c.name }}
            </option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit">Save</button>
          <button type="button" @click="$emit('close')">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, defineProps, defineEmits } from 'vue';
import Swal from 'sweetalert2';
import api from '../../../../axios.js';

const props = defineProps({
  show: Boolean,
  teacher: Object
});

const emit = defineEmits(['close', 'updated']);

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  class_id: ''
});

const classrooms = ref([]);

// Load unassigned classrooms from backend
const fetchClassrooms = async () => {
  try {
    const res = await api.get('/admin/classrooms/available');
    classrooms.value = res.data;
  } catch (err) {
    console.error('Failed to fetch classrooms', err);
  }
};

// Populate form when teacher prop changes
watch(() => props.teacher, (newVal) => {
  if (newVal) {
    form.value = {
      name: newVal.name,
      email: newVal.email,
      password: '',
      password_confirmation: '',
      class_id: '' // reset class selection
    };
  }
}, { immediate: true });

// Submit update
const submitForm = async () => {
  if (form.value.password && form.value.password !== form.value.password_confirmation) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match' });
    return;
  }

  try {
    const payload = {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password || undefined,
      password_confirmation: form.value.password_confirmation || undefined,
      class_id: form.value.class_id || undefined
    };

    await api.put(`/admin/teachers/${props.teacher.id}`, payload);

    Swal.fire({ icon: 'success', title: 'Updated!', text: 'Teacher updated successfully', timer: 2000, showConfirmButton: false });
    emit('updated');
    emit('close');
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: err.response?.data?.message || 'Failed to update teacher' });
    console.error(err);
  }
};

onMounted(() => {
  fetchClassrooms();
});
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.5);
  display:flex;
  justify-content:center;
  align-items:center;
  z-index:1000;
}
.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 5px;
  width: 400px;
}
.form-group {
  margin-bottom: 10px;
}
.form-group label {
  display:block;
  margin-bottom:4px;
}
.form-group input,
.form-group select {
  width:100%;
  padding:6px;
  border-radius:4px;
  border:1px solid #ccc;
}
.form-actions {
  margin-top: 10px;
  display: flex;
  justify-content: flex-end;
}
button {
  margin-left: 5px;
  padding: 6px 12px;
  cursor: pointer;
}
</style>
