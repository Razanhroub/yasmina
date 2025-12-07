<template>
  <div class="student-dashboard">
    <h2>Student Dashboard</h2>

  

    <!-- Render Components -->
    <div class="student-content">
      <Profile v-if="currentView === 'profile'" />
      <MyClassroom v-else-if="currentView === 'classroom'" />
    </div>
  </div>
</template>

<script>
import Profile from './Profile.vue';
import MyClassroom from './MyClassroom.vue';

export default {
  name: "StudentDashboard",
  components: { Profile, MyClassroom },
  data() {
    return {
      currentView: 'profile' // default view
    };
  }
};
</script>


