<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <h3>Edit User Role</h3>
      
      <form @submit.prevent="updateRole">

        <!-- Role toggle -->
        <div class="form-group">
          <label>Role</label>
          <select v-model="form.role">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
          </select>
        </div>

        <!-- Buttons -->
        <div class="form-group">
          <button type="submit">Update Role</button>
          <button type="button" @click="$emit('close')">Cancel</button>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import api from '../../../../axios.js';
import Swal from 'sweetalert2';

const props = defineProps({
  show: Boolean,
  student: Object
});

const emit = defineEmits(['close', 'updated']);

const form = ref({ role: 'student' });

// Populate form with current role
watch(
  () => props.student,
  (user) => {
    if (user) {
      form.value.role = user.user.roles[0]?.name || 'student';
    }
  },
  { immediate: true }
);

// Update role
const updateRole = async () => {
  try {
    // PUT request to backend to change role
    await api.put(`/admin/users/${props.student.user.id}/role`, { role: form.value.role });

    Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: `Role changed to ${form.value.role}`,
      timer: 2000,
      showConfirmButton: false
    });

    emit('updated');
    emit('close');

  } catch (err) {
    console.error(err);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to update role'
    });
  }
};
</script>
