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

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
}

.modal-container {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}

.modal-container h3 {
  margin-bottom: 15px;
  text-align: center;
}

.modal-container p {
  margin-bottom: 20px;
  text-align: center;
}

.modal-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.btn-delete {
  background-color: #e53e3e;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-delete:hover:not(:disabled) {
  background-color: #c53030;
}

.btn-delete:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel {
  background-color: #718096;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-cancel:hover:not(:disabled) {
  background-color: #4a5568;
}

.btn-cancel:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>