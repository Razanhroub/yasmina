import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import AdminDashboard from '../views/dashboard/admin/AdminDashboard.vue';
import TeacherDashboard from '../views/dashboard/teacher/TeacherDashboard.vue';
import StudentDashboard from '../views/dashboard/student/StudentDashboard.vue';



const routes = [
  { path: '/', redirect: '/register' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
 {
  path: '/dashboard/admin',
  component: AdminDashboard,
  meta: { requiresAuth: true, role: 'admin' }
},
{
  path: '/dashboard/teacher',
  component: TeacherDashboard,
  meta: { requiresAuth: true, role: 'teacher' }
},
{
  path: '/dashboard/student',
  component: StudentDashboard,
  meta: { requiresAuth: true, role: 'student' }
}


];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  // If logged in, prevent going to login/register
  if (token && (to.name === 'Login' || to.name === 'Register')) {
    return next('/dashboard');
  }

  // If not logged in, protect routes with requiresAuth meta
  if (!token && to.meta.requiresAuth) {
    return next('/login');
  }

  next(); // allow access
});

export default router;
