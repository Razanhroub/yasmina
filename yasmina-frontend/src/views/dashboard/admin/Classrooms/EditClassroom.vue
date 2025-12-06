<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <h3>Edit Classroom</h3>

      <div class="form-group">
        <label>Name</label>
        <input type="text" v-model="localForm.name" />
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea v-model="localForm.description"></textarea>
      </div>

      <div class="form-group">
        <label>Teacher</label>
        <select v-model="localForm.teacher_id">
          <option value="">N/A</option>
          <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
            {{ teacher.name }}
          </option>
        </select>
      </div>

      <div>
        <button @click="saveEdit">Save</button>
        <button @click="$emit('close')">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';
import Swal from 'sweetalert2';
import api from '../../../../axios.js';

const props = defineProps({
  show: Boolean,
  classroom: Object,
  teachers: Array
});

const emit = defineEmits(['close', 'updated']);

const localForm = ref({
  name: '',
  description: '',
  teacher_id: ''
});

// Watch for classroom prop change and populate localForm
watch(() => props.classroom, (newVal) => {
  if (newVal) {
    localForm.value = {
      name: newVal.name,
      description: newVal.description,
      teacher_id: newVal.teacher ? newVal.teacher.id : ''
    };
  }
}, { immediate: true });

// Save edit
const saveEdit = async () => {
  try {
    await api.put(`/admin/classrooms/${props.classroom.id}`, localForm.value);
    Swal.fire({
      icon: 'success',
      title: 'Updated!',
      text: 'Classroom updated successfully',
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
      text: err.response?.data?.message || 'Failed to update classroom'
    });
  }
};
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
.form-group textarea,
.form-group select {
  width:100%;
  padding:6px;
  border-radius:4px;
  border:1px solid #ccc;
}
button {
  margin-right: 5px;
  padding: 6px 12px;
  cursor: pointer;
}
</style>
