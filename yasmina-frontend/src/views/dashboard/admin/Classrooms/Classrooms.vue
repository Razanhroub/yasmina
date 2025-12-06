<template>
  <div class="module-page">
    <h2>Classroom Management</h2>
    <CreateClassroom @created="fetchClassrooms" />

    <table v-if="classrooms.length" class="classroom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Teacher</th>
          <th>Students</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="classroom in classrooms" :key="classroom.id">
          <td>{{ classroom.id }}</td>
          <td>{{ classroom.name }}</td>
          <td>{{ classroom.description || '-' }}</td>
          <td>{{ classroom.teacher ? classroom.teacher.name : '-' }}</td>
          <td>{{ classroom.students ? classroom.students.length : 0 }}</td>

          <td>
            <button @click="openEdit(classroom)">Edit</button>
          </td>
          <td>
            <DeleteClassroom :id="classroom.id" @deleted="fetchClassrooms" />
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Use EditClassroom as modal -->
    <EditClassroom
      :show="editingClassroom !== null"
      :classroom="editingClassroom"
      :teachers="teachers"
      @close="editingClassroom = null"
      @updated="fetchClassrooms"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../../../axios.js';
import CreateClassroom from './CreateClassroom.vue';
import DeleteClassroom from './DeleteClassroom.vue';
import EditClassroom from './EditClassroom.vue'; // fixed import

const classrooms = ref([]);
const teachers = ref([]);
const editingClassroom = ref(null);

const fetchClassrooms = async () => {
  try {
    const res = await api.get('/admin/classrooms');
    classrooms.value = res.data;
  } catch (err) {
    console.error(err);
  }
};

const fetchTeachers = async () => {
  try {
    const res = await api.get('/admin/teachers');
    teachers.value = res.data;
  } catch (err) {
    console.error(err);
  }
};

const openEdit = (classroom) => {
  editingClassroom.value = classroom;
};

onMounted(() => {
  fetchClassrooms();
  fetchTeachers();
});
</script>

<style scoped>
.module-page {
  padding: 20px;
}

.classroom-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.classroom-table th,
.classroom-table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.classroom-table th {
  background-color: #f0f0f0;
}

button {
  margin-right: 5px;
  padding: 6px 12px;
  cursor: pointer;
}
</style>
