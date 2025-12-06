<template>
  <button @click="confirmDelete">Delete</button>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import Swal from 'sweetalert2';
import api from '../../../../axios.js';

const props = defineProps({
  id: Number
});
const emit = defineEmits(['deleted']);

const confirmDelete = async () => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This will delete the classroom!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/admin/classrooms/${props.id}`);
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Classroom deleted successfully',
        timer: 2000,
        showConfirmButton: false
      });
      emit('deleted', props.id); // Notify parent to refresh table
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Failed to delete classroom'
      });
    }
  }
};
</script>
