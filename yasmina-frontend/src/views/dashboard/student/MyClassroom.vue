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

