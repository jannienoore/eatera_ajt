@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-12">
  <h2 class="text-2xl font-bold mb-4">Login</h2>
  <div id="errorMsg" class="mb-4 p-3 bg-red-100 text-red-700 rounded hidden"></div>
  <form id="loginForm" class="space-y-4">
    <div>
      <label class="block text-sm font-semibold mb-1">Email</label>
      <input name="email" type="email" required class="w-full border rounded px-3 py-2" placeholder="your@email.com" />
    </div>
    <div>
      <label class="block text-sm font-semibold mb-1">Password</label>
      <input name="password" type="password" required class="w-full border rounded px-3 py-2" placeholder="••••••••" />
    </div>
    <button type="submit" class="w-full bg-rose-400 text-white px-4 py-2 rounded font-semibold hover:bg-rose-500">Login</button>
  </form>
  <p class="text-center text-sm text-gray-600 mt-4">
    Don't have an account? <a href="/register" class="text-rose-400 font-semibold">Register here</a>
  </p>
</div>

<script>
  const form = document.getElementById('loginForm');
  const errorMsg = document.getElementById('errorMsg');
  const submitBtn = form.querySelector('button[type="submit"]');

  async function csrf() {
    try {
      console.log('Getting CSRF cookie from /sanctum/csrf-cookie');
      const response = await fetch('/sanctum/csrf-cookie', { 
        credentials: 'include',
        method: 'GET'
      });
      console.log('CSRF response status:', response.status);
      
      // Check for X-CSRF-TOKEN header or cookie
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      console.log('CSRF token from meta:', token ? token.substring(0, 20) + '...' : 'NOT FOUND');
      
      // Get CSRF token from cookie
      const csrfCookie = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='));
      console.log('CSRF cookie XSRF-TOKEN:', csrfCookie ? 'found' : 'NOT FOUND');
      
      return response;
    } catch (err) {
      console.error('CSRF fetch error:', err);
      throw err;
    }
  }

  async function login(data) {
    console.log('login() called with:', data);
    console.log('window.axios available?', !!window.axios);
    
    if (!window.axios) {
      throw new Error('Axios not initialized');
    }
    
    await csrf();
    await new Promise(resolve => setTimeout(resolve, 100));
    
    console.log('Making POST request to /api/login');
    console.log('Current axios defaults:', {
      baseURL: window.axios.defaults.baseURL,
      withCredentials: window.axios.defaults.withCredentials,
      headers: {
        'X-Requested-With': window.axios.defaults.headers.common['X-Requested-With'],
        'Authorization': window.axios.defaults.headers.common['Authorization']
      }
    });
    
    const response = await window.axios.post('/login', data);
    console.log('Login response received:', response.status, response.data);
    
    // Store token for Bearer auth if needed
    if (response.data.token) {
      console.log('Token received:', response.data.token.substring(0, 30) + '...');
      localStorage.setItem('auth_token', response.data.token);
      window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
      console.log('Token stored and Authorization header set');
    }
    return response;
  }

  async function fetchUser() {
    return window.axios.get('/user');
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorMsg.classList.add('hidden');
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Logging in...';
    
    const email = form.email.value;
    const password = form.password.value;

    try {
      console.log('=== LOGIN ATTEMPT ===');
      console.log('Email:', email);
      console.log('Password length:', password.length);
      
      const loginRes = await login({ email, password });
      console.log('Login response status:', loginRes.status);
      console.log('Login response data:', loginRes.data);
      
      if (!loginRes.data.token) {
        throw new Error('No token received from login');
      }
      
      console.log('Token stored:', loginRes.data.token.substring(0, 20) + '...');
      
      const userRes = await fetchUser();
      console.log('fetchUser response status:', userRes.status);
      const user = userRes.data;
      console.log('User object:', user);
      console.log('User role:', user.role);
      
      if (user.role === 'admin') {
        console.log('Admin login detected, redirecting to /admin/dashboard');
        window.location.href = '/admin/dashboard';
      } else {
        console.log('Regular user login detected, redirecting to /dashboard');
        window.location.href = '/dashboard';
      }
    } catch (err) {
      console.error('=== LOGIN ERROR ===');
      console.error('Error object:', err);
      if (err.response) {
        console.error('Response status:', err.response.status);
        console.error('Response data:', err.response.data);
        console.error('Response headers:', err.response.headers);
      }
      
      let message = 'Login failed. Please check your credentials.';
      
      if (err.message) {
        message = err.message;
      }
      
      // Handle validation errors (422)
      if (err.response?.status === 422 && err.response?.data?.errors) {
        const errors = err.response.data.errors;
        message = Object.keys(errors).map(key => {
          return Array.isArray(errors[key]) ? errors[key][0] : errors[key];
        }).join(', ');
      } else if (err.response?.data?.message) {
        message = err.response.data.message;
      } else if (err.response?.data?.email) {
        message = Array.isArray(err.response.data.email) ? err.response.data.email[0] : err.response.data.email;
      }
      
      errorMsg.textContent = message;
      errorMsg.classList.remove('hidden');
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });
</script>

@endsection

