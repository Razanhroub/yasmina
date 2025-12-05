<template>
  
</template>

<script>
import { ref, onMounted } from 'vue';
import router from '../router';
import Header from '../components/Header.vue';

export default {
  components: {
    Header,
  },
  setup() {
    const user = ref(null);

    onMounted(() => {
      const storedUser = localStorage.getItem('user');
      const token = localStorage.getItem('token');

      if (!token || !storedUser) {
        router.push('/login');
        return;
      }

      const parsedUser = JSON.parse(storedUser);
      user.value = parsedUser;

      // Redirect based on role
      if (router.currentRoute.value.path === '/dashboard') {
        if (parsedUser.role === 'admin') {
          router.replace('/dashboard/admin');
        } else if (parsedUser.role === 'teacher') {
          router.replace('/dashboard/teacher');
        } else if (parsedUser.role === 'student') {
          router.replace('/dashboard/student');
        }
      }
    });

    return { user };
  },
};
</script>
