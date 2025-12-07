<template>
  <div class="classrooms-schedule">
    <h2>Classrooms Schedule</h2>
    <table class="table-classrooms">
      <thead>
        <tr>
          <th>Class Name</th>
          <th>Description</th>
          <th>Number of Students</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="classroom in classrooms" :key="classroom.id">
          <td>{{ classroom.name || 'N/A' }}</td>
          <td>{{ classroom.description || 'N/A' }}</td>
          <td>{{ classroom.students?.length || 0 }}</td>
          <td class="actions">
            <button class="btn-create" @click="toggleCreateForm(classroom.id)">
              {{ createFormId === classroom.id ? 'Cancel' : 'Create Student' }}
            </button>
            <button class="btn-edit" @click="openEditModal(classroom)">Edit</button>
            <button class="btn-view" @click="openViewModal(classroom)">View</button>
          </td>
        </tr>

        <tr v-if="createFormId" class="create-student-row">
          <td colspan="4">
            <CreateStudent :classId="createFormId" @created="handleStudentCreated" />
          </td>
        </tr>

        <tr v-if="classrooms.length === 0">
          <td colspan="4" class="no-classrooms">No classrooms found</td>
        </tr>
      </tbody>
    </table>

    <!-- Modals -->
    <EditClass 
      v-if="selectedClassroom" 
      :classroom="selectedClassroom" 
      @close="selectedClassroom = null"
      @updated="updateClassroomData"
    />

    <Students
      v-if="viewClassroomStudents"
      :students="viewClassroomStudents"
      :classroomId="viewClassroomId"
      @close="closeViewModal"
      @student-deleted="handleStudentDeleted"
    />
  </div>
</template>

<script>
import api from '../../../axios';
import EditClass from './EditClass.vue';
import Students from './Students.vue';
import CreateStudent from './CreateStudent.vue';

export default {
  name: "Classrooms",
  components: { EditClass, Students, CreateStudent },
  data() {
    return {
      classrooms: [],
      selectedClassroom: null,
      viewClassroomStudents: null,
      viewClassroomId: null,
      createFormId: null
    };
  },
  created() {
    this.fetchClassrooms();
  },
  methods: {
    async fetchClassrooms() {
      try {
        const res = await api.get('/teacher/classrooms');
        this.classrooms = res.data.map(c => ({ ...c, students: c.students || [] }));
      } catch (err) {
        console.error('Error fetching classrooms:', err.response || err);
      }
    },
    openEditModal(classroom) {
      if (!classroom) return;
      this.selectedClassroom = { ...classroom };
    },
    updateClassroomData(updatedClassroom) {
      const index = this.classrooms.findIndex(c => c.id === updatedClassroom.id);
      if (index !== -1) {
        const students = this.classrooms[index].students || [];
        this.classrooms.splice(index, 1, { ...updatedClassroom, students });
      }
      this.selectedClassroom = null;
    },
    openViewModal(classroom) {
      if (!classroom?.students) return;
      this.viewClassroomId = classroom.id;
      this.viewClassroomStudents = classroom.students
        .map(s => ({
          id: s.user?.id,
          birth_of_date: s.birth_of_date,
          country: s.country,
          name: s.user?.name || 'N/A',
          email: s.user?.email || 'N/A',
        }))
        .filter(s => s.id);
    },
    closeViewModal() {
      this.viewClassroomStudents = null;
      this.viewClassroomId = null;
    },
    toggleCreateForm(classroomId) {
      this.createFormId = this.createFormId === classroomId ? null : classroomId;
    },
    handleStudentCreated(newStudent) {
      this.createFormId = null;
      
      // Update the specific classroom without refetching all
      const classroom = this.classrooms.find(c => c.id === newStudent.class_id);
      if (classroom) {
        if (!classroom.students) {
          classroom.students = [];
        }
        classroom.students.push(newStudent);
        // Trigger reactivity
        this.classrooms = [...this.classrooms];
      }
    },
    handleStudentDeleted(deletedId) {
      // Update all classrooms to remove the deleted student
      this.classrooms = this.classrooms.map(classroom => {
        if (classroom.students) {
          return {
            ...classroom,
            students: classroom.students.filter(s => s.user?.id !== deletedId)
          };
        }
        return classroom;
      });

      // Update view modal if open
      if (this.viewClassroomStudents) {
        this.viewClassroomStudents = this.viewClassroomStudents.filter(s => s.id !== deletedId);
      }
    }
  }
}
</script>