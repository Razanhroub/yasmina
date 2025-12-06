<template>
  <div class="not-found">
    <h2>404 - Page Not Found</h2>
    <p>The page you are looking for does not exist.</p>
    <button @click="goBack">Go Back</button>
  </div>
</template>

<script>
export default {
  methods: {
    goBack() {
      this.$router.back(); // Goes to previous page
    }
  }
};
</script>

<style scoped>
.not-found {
  text-align: center;
  margin-top: 50px;
}
button {
  padding: 10px 20px;
  margin-top: 20px;
  cursor: pointer;
}
</style>
