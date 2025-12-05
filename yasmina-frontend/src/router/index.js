import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';

const routes = [
  { path: '/', redirect: '/register' },
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const expiry = localStorage.getItem('token_expiry');

  // If token exists, check expiry
  if (token && expiry) {
    if (new Date().getTime() > Number(expiry)) {
      // Token expired
      localStorage.removeItem('token');
      localStorage.removeItem('token_expiry');
      return next('/login');
    }

    // If logged in, prevent going to login/register
    if (to.name === 'Login' || to.name === 'Register') {
      return next('/dashboard');
    }
  }

  // If not logged in, protect routes with requiresAuth meta
  if (!token && to.meta.requiresAuth) {
    return next('/login');
  }

  next(); // allow access

});

export default router;
