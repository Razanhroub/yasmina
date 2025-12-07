<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-container">
      <h3>Delete Student</h3>
      <p>Are you sure you want to delete <strong>{{ student.name }}</strong>?</p>
      <div class="modal-actions">
        <button class="btn-delete" @click="confirmDelete" :disabled="isDeleting">
          {{ isDeleting ? 'Deleting...' : 'Yes, Delete' }}
        </button>
        <button class="btn-cancel" @click="$emit('close')" :disabled="isDeleting">Cancel</button>
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
      required: true
    }
  },
  data() {
    return {
      isDeleting: false
    };
  },
  methods: {
    async confirmDelete() {
      if (this.isDeleting) return;
      
      this.isDeleting = true;
      
      try {
        await api.delete(`/teacher/students/${this.student.id}`);
        
        // Emit deleted event immediately for instant UI update
        this.$emit('deleted', this.student.id);
        
        // Show success message without waiting
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: `${this.student.name} has been deleted`,
          timer: 1500,
          showConfirmButton: false
        });
        
        // Close modal
        this.$emit('close');
      } catch (err) {
        console.error(err);
        this.isDeleting = false;
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
