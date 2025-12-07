<template>
  <div class="modal-overlay">
    <div class="modal-container">
      <h3>Edit Student</h3>
      <form @submit.prevent="submitEdit">
        <div class="form-group">
          <label>Name</label>
          <input type="text" v-model="form.name" required />
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="form.email" required />
        </div>

        <div class="form-group">
          <label>Birth Date</label>
          <input type="date" v-model="form.birth_of_date" />
        </div>

        <div class="form-group">
          <label>Country</label>
          <input type="text" v-model="form.country" />
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-save">Save</button>
          <button type="button" class="btn-cancel" @click="$emit('close')">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from '../../../axios';
import Swal from 'sweetalert2';

export default {
  name: 'EditStudent',
  props: {
    student: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: { ...this.student }
    };
  },
  methods: {
    async submitEdit() {
      try {
        await api.put(`/teacher/students/${this.student.id}`, this.form);
        Swal.fire('Success', 'Student updated successfully!', 'success');
        this.$emit('updated', this.form);
      } catch (err) {
        Swal.fire('Error', err.response?.data?.message || 'Failed to update student', 'error');
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

.form-group {
  margin-bottom: 10px;
}

.form-group label {
  display: block;
  font-weight: 500;
  margin-bottom: 4px;
}

.form-group input {
  width: 100%;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.btn-save {
  background-color: #38c172;
  color: #fff;
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-save:hover {
  background-color: #2f9e5b;
}

.btn-cancel {
  background-color: #e53e3e;
  color: #fff;
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-cancel:hover {
  background-color: #c53030;
}
</style>
