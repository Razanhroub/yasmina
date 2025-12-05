<template>
  <div>
    <Header :user="user" />
    <div class="container">
      <h2>Dashboard</h2>
      <div v-if="user">
        <div v-if="user.role === 'admin'">
          <h3>Admin Panel</h3>
          <p>Manage all users, classrooms, and students.</p>
        </div>
        <div v-else-if="user.role === 'teacher'">
          <h3>Teacher Panel</h3>
          <p>View and manage your own classrooms and students.</p>
        </div>
        <div v-else-if="user.role === 'student'">
          <h3>Student Panel</h3>
          <p>View your assigned classroom and update your profile.</p>
        </div>
      </div>
      <p v-else>Loading user info...</p>
    </div>
  </div>
</template>

<script>
import Header from '../components/Header.vue';
import { ref, onMounted } from 'vue';
import api from '../axios';
import router from '../router';

export default {
  components: { Header },
  setup() {
    const user = ref(null);

    onMounted(async () => {
      const token = localStorage.getItem('token');
      if (!token) {
        router.push('/login');
        return;
      }

      try {
        const res = await api.get('/user', {
          headers: { Authorization: `Bearer ${token}` }
        });
        user.value = res.data.user;
      } catch (err) {
        localStorage.removeItem('token');
        router.push('/login');
      }
    });

    return { user };
  }
};
</script>
