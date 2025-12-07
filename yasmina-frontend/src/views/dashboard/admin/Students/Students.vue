<template>
  <div class="module-page">
    <h2>Students Management</h2>

    <!-- Create Student Component -->
    <CreateStudent @created="fetchStudents" />

    <table v-if="students.length" class="students-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Classroom</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="student in students" :key="student.id">
          <td>{{ student.id }}</td>
          <td>{{ student.name }}</td>
          <td>{{ student.email }}</td>
          <td>Student</td>
          <td>{{ student.student?.classroom?.name || '-' }}</td>
          <td>
            <!-- Buttons per student -->
            <button @click="toggleRole(student)">Change to teacher</button>
            <button @click="deleteStudent(student)">Delete</button>
            <button @click="openAssignModal(student.id)">Assign Classroom</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-else>Loading students...</p>

    <!-- Assign Classroom Modal -->
    <AssignClassroom 
      ref="assignModal" 
      :studentId="selectedStudentId" 
      @assigned="fetchStudents"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../../axios.js';
import CreateStudent from './CreateStudent.vue';
import AssignClassroom from './AssignClassroom.vue';
import Swal from 'sweetalert2';

const students = ref([]);
const selectedStudentId = ref(null);
const assignModal = ref(null);

// Fetch all students
const fetchStudents = async () => {
  try {
    const res = await api.get('/admin/students');
    students.value = res.data.data || [];
  } catch (err) {
    console.error(err);
  }
};

// Assign Role (toggle)
const toggleRole = async (student) => {
  try {
    await api.put(`/admin/users/${student.id}/toggle-role`, { role: 'teacher' });
    Swal.fire({
      icon: 'success',
      title: 'Role Updated',
      text: `${student.name} is now a Teacher`,
      timer: 2000,
      showConfirmButton: false
    });
    fetchStudents();
  } catch (err) {
    console.error(err);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: err.response?.data?.message || 'Failed to update role'
    });
  }
};

// Soft delete student
const deleteStudent = async (student) => {
  const confirmResult = await Swal.fire({
    title: `Are you sure?`,
    text: `This will soft delete ${student.name} and all related records.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (confirmResult.isConfirmed) {
    try {
      await api.delete(`/admin/students/${student.id}`);
      Swal.fire({
        icon: 'success',
        title: 'Deleted',
        text: `${student.name} has been deleted`,
        timer: 2000,
        showConfirmButton: false
      });
      fetchStudents();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Failed to delete student'
      });
    }
  }
};

// Open Assign Classroom modal for selected student
const openAssignModal = (studentId) => {
  selectedStudentId.value = studentId;
  assignModal.value.openModal();
};

onMounted(fetchStudents);
</script>

<style scoped>
.students-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
.students-table th,
.students-table td {
  border: 1px solid #ddd;
  padding: 8px;
}
.students-table th {
  background-color: #f4f4f4;
}
button {
  padding: 6px 12px;
  cursor: pointer;
  margin-right: 5px;
}
p {
  margin-top: 10px;
  font-style: italic;
}
</style>
