import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // your backend URL
  headers: {
    'Content-Type': 'application/json'
  }
});

// Add token automatically to all requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token'); // token stored after login/register
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
