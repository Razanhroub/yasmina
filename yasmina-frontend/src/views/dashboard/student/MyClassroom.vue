<template>
  <div class="classroom-card" v-if="classroom">
    <h3>{{ classroom.name }}</h3>
    <p>{{ classroom.description }}</p>
    <p><strong>Teacher:</strong> {{ classroom.teacher?.name || 'N/A' }}</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  classroom: Object
});
</script>

<style scoped>
.classroom-card {
  border: 1px solid #ccc;
  padding: 15px;
  margin-top: 10px;
  border-radius: 8px;
  background: #f9f9f9;
}
.classroom-card h3 {
  margin-bottom: 8px;
}
</style>
