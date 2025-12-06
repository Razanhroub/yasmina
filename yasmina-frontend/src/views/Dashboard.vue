<template>
  <div>
    <!-- Common Header -->
    <Header :user="user" />

    <!-- Dashboard content -->
    <div class="container">
      <h2>Dashboard</h2>

      <div v-if="user">
        <!-- Render the correct dashboard based on the route -->
        <router-view />
      </div>

      <p v-else>Loading user info...</p>
    </div>
  </div>
</template>

<script>
import Header from '../components/Header.vue';

export default {
  components: { Header },
  data() {
    return {
      user: null
    };
  },
  created() {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  }
};
</script>

<style scoped>
.container {
  margin-top: 20px;
}
</style>
