@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8 mb-8">
  <h2 class="text-3xl font-bold mb-6">Register as Calomate</h2>
  <div id="errorMsg" class="mb-4 p-3 bg-red-100 text-red-700 rounded hidden"></div>
  <form id="registerForm" class="space-y-6">
    <!-- Basic Info -->
    <div class="border-b pb-6">
      <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Full Name</label>
          <input name="name" required class="w-full border rounded px-3 py-2" placeholder="Your name" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Email</label>
          <input name="email" type="email" required class="w-full border rounded px-3 py-2" placeholder="your@email.com" />
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="border-b pb-6">
      <h3 class="text-lg font-semibold mb-4">Security</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Password</label>
          <input name="password" type="password" required class="w-full border rounded px-3 py-2" placeholder="••••••••" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Confirm Password</label>
          <input name="password_confirmation" type="password" required class="w-full border rounded px-3 py-2" placeholder="••••••••" />
        </div>
      </div>
    </div>

    <!-- Profile Info -->
    <div class="border-b pb-6">
      <h3 class="text-lg font-semibold mb-4">Profile Information</h3>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Gender</label>
          <select name="gender" required class="w-full border rounded px-3 py-2">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Date of Birth</label>
          <input name="date_of_birth" type="date" required class="w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Weight (kg)</label>
          <input name="weight" type="number" step="0.1" min="20" max="500" required class="w-full border rounded px-3 py-2" placeholder="70" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Height (cm)</label>
          <input name="height" type="number" step="0.1" min="50" max="300" required class="w-full border rounded px-3 py-2" placeholder="170" />
        </div>
      </div>
    </div>

    <!-- Diet Goals -->
    <div class="pb-6">
      <h3 class="text-lg font-semibold mb-4">Diet Goals</h3>
      <div>
        <label class="block text-sm font-semibold mb-1">Diet Goal</label>
        <select name="diet_goal" required class="w-full border rounded px-3 py-2">
          <option value="">Select Goal</option>
          <option value="deficit">Weight Loss (Deficit -300 cal)</option>
          <option value="maintain">Maintain Weight</option>
          <option value="bulking">Weight Gain (Surplus +300 cal)</option>
        </select>
      </div>
      <p class="text-xs text-gray-500 mt-2">Target calories will be automatically calculated based on your profile</p>
    </div>

    <button type="submit" class="w-full bg-rose-400 text-white px-4 py-3 rounded font-semibold hover:bg-rose-500 text-lg">Register</button>
  </form>
  <p class="text-center text-sm text-gray-600 mt-4">
    Already have an account? <a href="/login" class="text-rose-400 font-semibold">Login here</a>
  </p>
</div>

<script>
  const form = document.getElementById('registerForm');
  const errorMsg = document.getElementById('errorMsg');
  const submitBtn = form.querySelector('button[type="submit"]');

  async function csrf() {
    try {
      await fetch('/sanctum/csrf-cookie', { 
        credentials: 'include',
        method: 'GET'
      });
    } catch (err) {
      console.error('CSRF fetch error:', err);
    }
  }

  async function register(data) {
    await csrf();
    await new Promise(resolve => setTimeout(resolve, 100));
    return window.axios.post('/register', data);
  }

  async function login(data) {
    await csrf();
    await new Promise(resolve => setTimeout(resolve, 100));
    const response = await window.axios.post('/login', data);
    // Store token for Bearer auth if needed
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
      window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
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
    submitBtn.textContent = 'Registering...';
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    if (data.password !== data.password_confirmation) {
      errorMsg.textContent = 'Passwords do not match';
      errorMsg.classList.remove('hidden');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Register';
      return;
    }

    try {
      console.log('Attempting registration with:', data);
      const regRes = await register(data);
      console.log('Registration response:', regRes.data);
      
      // After successful registration, redirect to login page
      // User must login with their credentials to access the app
      window.location.href = '/login';
    } catch (err) {
      console.error('Registration error:', err);
      let message = 'Registration failed. Please try again.';
      
      if (err.response?.data?.message) {
        message = err.response.data.message;
      } else if (err.response?.data?.errors) {
        // Handle multiple validation errors
        const errors = err.response.data.errors;
        message = Object.keys(errors).map(key => errors[key].join(', ')).join(' | ');
      } else if (err.response?.data?.email) {
        message = Array.isArray(err.response.data.email) ? err.response.data.email[0] : err.response.data.email;
      }
      
      errorMsg.textContent = message;
      errorMsg.classList.remove('hidden');
      submitBtn.disabled = false;
      submitBtn.textContent = 'Register';
    }
  });
</script>

@endsection

