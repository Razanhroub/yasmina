import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import AdminDashboard from '../views/dashboard/admin/AdminDashboard.vue';
import TeacherDashboard from '../views/dashboard/teacher/TeacherDashboard.vue';
import StudentDashboard from '../views/dashboard/student/StudentDashboard.vue';
import NotFound from '../views/NotFound.vue';


const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },

{
  path: '/dashboard',
  component: Dashboard,
  meta: { requiresAuth: true },
  children: [
    { path: 'admin', component: AdminDashboard, meta: { role: 'admin' } },
    { path: 'teacher', component: TeacherDashboard, meta: { role: 'teacher' } },
    { path: 'student', component: StudentDashboard, meta: { role: 'student' } },
    
    { path: ':pathMatch(.*)*', component: NotFound }
  ]
},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null;

  // Not logged in, redirect to login if route requires auth
  if (!token && to.meta.requiresAuth) {
    return next('/login');
  }

  // Already logged in, prevent accessing login/register
  if (token && (to.name === 'Login' || to.name === 'Register')) {
    return next('/dashboard');
  }

  // Protect routes by role
  if (to.meta.role && user?.role !== to.meta.role) {
    // Redirect to their own dashboard instead of empty page
    if (user.role === 'admin') return next('/dashboard/admin');
    if (user.role === 'teacher') return next('/dashboard/teacher');
    if (user.role === 'student') return next('/dashboard/student');
  }

  // Redirect /dashboard to correct role
  if (token && to.path === '/dashboard') {
    if (user.role === 'admin') return next('/dashboard/admin');
    if (user.role === 'teacher') return next('/dashboard/teacher');
    if (user.role === 'student') return next('/dashboard/student');
  }

  next();
});

export default router;
