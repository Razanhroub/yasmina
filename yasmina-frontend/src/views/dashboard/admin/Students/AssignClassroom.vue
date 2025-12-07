<template>
  <div v-if="showModal" class="modal">
    <div class="modal-content">
      <h3>Assign Student to Classroom</h3>

      <select v-model="selectedClassroom">
        <option value="" disabled>Select Classroom</option>
        <option v-for="cls in classrooms" :key="cls.id" :value="cls.id">
          {{ cls.name }}
        </option>
      </select>

      <div class="modal-actions">
        <button @click="assignClassroom" :disabled="!selectedClassroom">Assign</button>
        <button @click="closeModal">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../../../../axios.js';
import Swal from 'sweetalert2';

const props = defineProps({
  studentId: { type: Number, required: true }
});

const emit = defineEmits(['assigned']);
const showModal = ref(false);
const classrooms = ref([]);
const selectedClassroom = ref('');

// Open modal and fetch classrooms dynamically
const openModal = async () => {
  showModal.value = true;
  selectedClassroom.value = '';

  try {
    const res = await api.get('/admin/classrooms');
    
    // Try different possible response structures
    let classroomData = [];
    
    if (res.data.data && Array.isArray(res.data.data)) {
      classroomData = res.data.data;
    } else if (Array.isArray(res.data)) {
      classroomData = res.data;
    } else if (res.data.classrooms && Array.isArray(res.data.classrooms)) {
      classroomData = res.data.classrooms;
    }
    
    // Map to id and name
    classrooms.value = classroomData.map(c => ({ 
      id: c.id, 
      name: c.name || c.classroom_name || `Classroom ${c.id}`
    }));
    
    if (classrooms.value.length === 0) {
      Swal.fire('Info', 'No classrooms available', 'info');
    }
  } catch (err) {
    console.error('Error fetching classrooms:', err);
    Swal.fire('Error', 'Failed to fetch classrooms', 'error');
  }
};

// Close modal
const closeModal = () => {
  showModal.value = false;
  selectedClassroom.value = '';
};

defineExpose({ openModal });

// Assign classroom to student
const assignClassroom = async () => {
  if (!selectedClassroom.value) {
    Swal.fire('Error', 'Please select a classroom', 'error');
    return;
  }

  try {
    await api.put(`/admin/students/${props.studentId}/assign-classroom`, {
      class_id: selectedClassroom.value
    });
    Swal.fire('Success', 'Student assigned to classroom', 'success');
    emit('assigned');
    closeModal();
  } catch (err) {
    console.error(err);
    Swal.fire('Error', err.response?.data?.message || 'Failed to assign classroom', 'error');
  }
};
</script>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 6px;
  width: 300px;
}
.modal-actions {
  margin-top: 15px;
  display: flex;
  justify-content: flex-end;
}
.modal-actions button {
  margin-left: 10px;
}
.modal-actions button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>