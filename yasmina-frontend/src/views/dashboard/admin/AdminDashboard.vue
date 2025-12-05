<template>
  <dv><Header  /></dv>
  <div class="admin-dashboard">
    <!-- Navigation Buttons -->
    <div class="nav-buttons">
      <button 
        :class="{ active: currentView === 'students' }" 
        @click="currentView = 'students'">
        Students
      </button>
      <button 
        :class="{ active: currentView === 'teachers' }" 
        @click="currentView = 'teachers'">
        Teachers
      </button>
      <button 
        :class="{ active: currentView === 'classrooms' }" 
        @click="currentView = 'classrooms'">
        Classrooms
      </button>
    </div>

    <!-- Content -->
    <div class="content">
      <Students v-if="currentView === 'students'" />
      <Teachers v-if="currentView === 'teachers'" />
      <Classrooms v-if="currentView === 'classrooms'" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Students from './Students/Students.vue';
import Teachers from './Teachers/Teachers.vue';
import Classrooms from './Classrooms/Classrooms.vue';
import Header from '../../../components/Header.vue';

const currentView = ref('students'); // default view
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
}

.nav-buttons {
  margin-bottom: 20px;
}

.nav-buttons button {
  margin-right: 10px;
  padding: 8px 16px;
  cursor: pointer;
  background-color: #eee;
  border: none;
  border-radius: 4px;
  font-weight: bold;
  transition: background-color 0.2s;
}

.nav-buttons button.active {
  background-color: #3b82f6;
  color: white;
}

.nav-buttons button:hover {
  background-color: #cbd5e1;
}

.content {
  padding: 10px 0;
}
</style>
