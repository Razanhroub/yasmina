<template>
  <div>
    <button @click="openAssignModal">Assign to Classroom</button>

    <div v-if="showModal" class="modal">
      <div class="modal-content">
        <h3>Assign Student to Classroom</h3>

        <select v-model="selectedClassroom">
          <option value="" disabled>Select Classroom</option>
          <option v-for="cls in classrooms" :key="cls.id" :value="cls.id">
            {{ cls.name }} - {{ cls.teacher?.name || 'No teacher' }}
          </option>
        </select>

        <div class="modal-actions">
          <button @click="assignClassroom">Assign</button>
          <button @click="closeModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../../axios.js';
import Swal from 'sweetalert2';

const props = defineProps({
  studentId: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['assigned']);

const showModal = ref(false);
const classrooms = ref([]);
const selectedClassroom = ref('');

// Fetch all classrooms (admin/classrooms)
const fetchClassrooms = async () => {
  try {
    const res = await api.get('/admin/classrooms');
    classrooms.value = res.data || [];
  } catch (err) {
    console.error(err);
    Swal.fire('Error', 'Failed to fetch classrooms', 'error');
  }
};

onMounted(() => {
  fetchClassrooms();
});

const openAssignModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedClassroom.value = '';
};

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
    emit('assigned'); // refresh parent list
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
select {
  width: 100%;
  padding: 6px;
  margin-top: 10px;
}
</style>
