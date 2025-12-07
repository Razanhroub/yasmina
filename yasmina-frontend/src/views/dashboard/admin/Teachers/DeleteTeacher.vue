<template>
  <button @click="confirmDelete" class="delete-btn">Delete</button>
</template>

<script setup>
import Swal from 'sweetalert2';
import api from '../../../../axios.js';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  id: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['deleted']);

const confirmDelete = async () => {
  const result = await Swal.fire({
    icon: 'warning',
    title: 'Are you sure?',
    text: "This will delete the teacher permanently!",
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/admin/teachers/${props.id}`);
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Teacher has been deleted',
        timer: 2000,
        showConfirmButton: false
      });
      emit('deleted'); // trigger refresh in parent
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Failed to delete teacher'
      });
      console.error(err);
    }
  }
};
</script>


