<template>
  <div v-if="classroom" class="modal-overlay">
    <div class="modal-container">
      <h2>Edit Classroom: {{ classroom.name || 'N/A' }}</h2>
      <div class="form-group">
        <label>Class Name</label>
        <input v-model="form.name" type="text" placeholder="Enter class name" />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea v-model="form.description" placeholder="Enter description"></textarea>
      </div>
      <div class="modal-actions">
        <button class="btn-save" @click="updateClass">Save</button>
        <button class="btn-cancel" @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../../axios';
import Swal from 'sweetalert2';

export default {
  name: 'EditClass',
  props: {
    classroom: {
      type: Object,
      required: true,
      default: () => ({ name: '', description: '' })
    }
  },
  data() {
    return {
      form: {
        name: this.classroom.name || '',
        description: this.classroom.description || ''
      }
    };
  },
  watch: {
    classroom(newClass) {
      this.form.name = newClass.name || '';
      this.form.description = newClass.description || '';
    }
  },
  methods: {
    async updateClass() {
      try {
        const res = await api.put(`/teacher/classrooms/${this.classroom.id}`, this.form);
        this.$emit('updated', res.data);
        this.$emit('close');
        Swal.fire({ icon: 'success', title: 'Updated!', timer: 1000, showConfirmButton: false });
      } catch (err) {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: err.response?.data?.message || 'Failed to update class' });
      }
    }
  }
};
</script>
