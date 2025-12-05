<template>
  <button @click="logout">Logout</button>
</template>

<script>
import api from '../axios';
import router from '../router';

export default {
  setup() {
    const logout = async () => {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          await api.post('/logout', {}, {
            headers: { Authorization: `Bearer ${token}` }
          });
        }
      } catch (err) {
        console.log(err);
      } finally {
        // Clear localStorage
        localStorage.removeItem('token');
        localStorage.removeItem('user');

        // Redirect to login
        router.push('/login');
      }
    };

    return { logout };
  }
};
</script>
