<template>
  <div class="modal-overlay">
    <div class="modal-container">
      <h3>Delete Student</h3>
      <p>Are you sure you want to delete <strong>{{ student.name }}</strong>?</p>
      <div class="modal-actions">
        <button class="btn-delete" @click="confirmDelete">Yes, Delete</button>
        <button class="btn-cancel" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../../axios';
import Swal from 'sweetalert2';

export default {
  name: 'DeleteStudent',
  props: {
    student: {
      type: Object,
      required: true,
      default: () => ({ name: 'Unknown' })
    }
  },
  methods: {
    async confirmDelete() {
      const result = await Swal.fire({
        title: `Are you sure?`,
        text: `This will permanently delete ${this.student.name} and all related data.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      });

      if (!result.isConfirmed) return;

      try {
        await api.delete(`/teacher/students/${this.student.id}`);
        this.$emit('close');
        await Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: `${this.student.name} has been deleted`,
          timer: 1500,
          showConfirmButton: false
        });
        this.$emit('deleted', this.student.id);
      } catch (err) {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: err.response?.data?.message || 'Failed to delete student'
        });
      }
    }
  }
};
</script>
