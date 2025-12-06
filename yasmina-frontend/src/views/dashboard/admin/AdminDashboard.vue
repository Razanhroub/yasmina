<template>
  <div class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
      <button @click="currentView = 'students'">Students</button>
      <button @click="currentView = 'teachers'">Teachers</button>
      <button @click="currentView = 'classrooms'">Classrooms</button>
    </div>

    <!-- Render components directly -->
    <div class="admin-content">
      <Students v-if="currentView === 'students'" />
      <Teachers v-else-if="currentView === 'teachers'" />
      <Classrooms v-else-if="currentView === 'classrooms'" />
    </div>
  </div>
</template>

<script>
import Students from './Students/Students.vue';
import Teachers from './Teachers/Teachers.vue';
import Classrooms from './Classrooms/Classrooms.vue';

export default {
  name: "AdminDashboard",
  components: { Students, Teachers, Classrooms },
  data() {
    return {
      currentView: 'students' // default view
    };
  }
};
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
}
.nav-buttons button {
  margin-right: 10px;
  padding: 8px 12px;
  cursor: pointer;
}
.admin-content {
  margin-top: 20px;
}
</style>
