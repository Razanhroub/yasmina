<template>
  <header class="header">
    <div class="header-left">
      <h1>Welcome, {{ user?.name || 'Guest' }}!</h1>
    </div>
    <div class="header-right" v-if="user">
      <LogoutButton />
    </div>
  </header>
</template>

<script>
import { ref, onMounted } from 'vue';
import LogoutButton from './LogoutButton.vue';

export default {
  components: { LogoutButton },
  setup() {
    const user = ref(null);

    onMounted(() => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
      }
    });

    return { user };
  }
};
</script>
