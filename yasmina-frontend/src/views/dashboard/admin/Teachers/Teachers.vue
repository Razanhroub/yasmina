<template>
  <div class="module-page">
    <h2>Teacher Management</h2>
    <CreateTeacher @created="fetchTeachers" />

    <table v-if="teachers.length" class="teacher-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Assigned Classes</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="teacher in teachers" :key="teacher.id">
          <td>{{ teacher.id }}</td>
          <td>{{ teacher.name }}</td>
          <td>{{ teacher.email }}</td>
          <td>
            <span v-if="teacher.classrooms.length">
              {{ teacher.classrooms.map(c => c.name).join(', ') }}
            </span>
            <span v-else>Not Assigned</span>
          </td>
          <td>
            <button @click="openEdit(teacher)">Edit</button>
          </td>
          <td>
            <DeleteTeacher :id="teacher.id" @deleted="fetchTeachers" />
          </td>
        </tr>
      </tbody>
    </table>

    <EditTeacher
      :show="editingTeacher !== null"
      :teacher="editingTeacher"
      @close="editingTeacher = null"
      @updated="fetchTeachers"
    />
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../../axios.js';
import CreateTeacher from './CreateTeacher.vue';
import DeleteTeacher from './DeleteTeacher.vue';
import EditTeacher from './EditTeacher.vue';

const teachers = ref([]);
const editingTeacher = ref(null);

const fetchTeachers = async () => {
  try {
    const res = await api.get('/admin/teachers');
    teachers.value = res.data;
  } catch (err) {
    console.error(err);
  }
};

const openEdit = (teacher) => {
  editingTeacher.value = teacher;
};

onMounted(() => {
  fetchTeachers();
});
</script>

<style scoped>
.module-page { padding: 20px; }

.teacher-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.teacher-table th,
.teacher-table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.teacher-table th {
  background-color: #f0f0f0;
}

button {
  margin-right: 5px;
  padding: 6px 12px;
  cursor: pointer;
}
</style>
