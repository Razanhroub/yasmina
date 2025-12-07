<template>
  <div class="teacher-dashboard">
    <h2>Teacher Dashboard</h2>

    <div class="teacher-content">
      <Classrooms />
    </div>
  </div>
</template>

<script>
import Classrooms from './Classrooms.vue'

export default {
  name: "TeacherDashboard",
  components: { Classrooms }
}
</script>
