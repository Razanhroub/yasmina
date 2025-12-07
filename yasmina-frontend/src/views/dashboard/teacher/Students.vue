<template>
  <div class="students-table-container">
    <table class="table-students">
      <thead>
        <tr>
          <th>Name</th>
          <th>Birth Date</th>
          <th>Country</th>
          <th>Email</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="student in localStudents" :key="student.id">
          <td>{{ student.name || 'N/A' }}</td>
          <td>{{ formatDate(student.birth_of_date) || 'N/A' }}</td>
          <td>{{ student.country || 'N/A' }}</td>
          <td>{{ student.email || 'N/A' }}</td>
          <td>
            <DeleteStudent
              v-if="deletingStudentId === student.id"
              :student="student"
              @close="deletingStudentId = null"
              @deleted="handleStudentDeleted"
            />
            <button
              v-else
              class="btn-delete"
              @click="deletingStudentId = student.id"
            >
              Delete
            </button>
          </td>
        </tr>
        <tr v-if="localStudents.length === 0">
          <td colspan="5" class="no-students">No students found</td>
        </tr>
      </tbody>
    </table>

    <div class="students-actions">
      <button class="btn-close" @click="$emit('close')">Close</button>
    </div>
  </div>
</template>

<script>
import DeleteStudent from './DeleteStudent.vue';

export default {
  name: 'Students',
  components: { DeleteStudent },
  props: {
    students: {
      type: Array,
      required: true
    },
    classroomId: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      deletingStudentId: null,
      localStudents: [...this.students] // Create local copy to avoid prop mutation
    };
  },
  watch: {
    students: {
      handler(newStudents) {
        this.localStudents = [...newStudents];
      },
      deep: true
    }
  },
  methods: {
    formatDate(dateStr) {
      if (!dateStr) return null;
      const date = new Date(dateStr);
      return date.toLocaleDateString();
    },
    handleStudentDeleted(deletedId) {
      // Update local list immediately for instant UI feedback
      this.localStudents = this.localStudents.filter(s => s.id !== deletedId);
      this.deletingStudentId = null;
      
      // Notify parent to update main data
      this.$emit('student-deleted', deletedId);
    }
  }
};
</script>