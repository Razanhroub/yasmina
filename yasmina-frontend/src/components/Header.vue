<template>
  <header class="header">
    <div class="header-left">
      <h1>Welcome, {{ user?.name || 'Guest' }}!</h1>
    </div>
    <div class="header-right" v-if="user">
      <button @click="logout">Logout</button>
    </div>
  </header>
</template>

<script>
import api from '../axios';
import router from '../router';
import { ref, onMounted } from 'vue';

export default {
  props: {
    user: Object // receive user from parent or fetch it here
  },
  setup(props) {
    const logout = async () => {
      try {
        await api.post('/logout', {}, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        localStorage.removeItem('token');
        localStorage.removeItem('token_expiry');
        router.push('/login');
      } catch (err) {
        console.log(err);
      }
    };

    return {
      logout
    };
  }
};
</script>
