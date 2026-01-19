import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
// API base for axios - adjust if your API is hosted under a subpath
window.axios.defaults.baseURL = '/api';

// If there's a stored token, use it
const token = localStorage.getItem('auth_token');
if (token) {
  window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Add response interceptor to handle token refresh or errors
window.axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      // Clear token if unauthorized
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
