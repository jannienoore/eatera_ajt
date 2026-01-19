<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name','Eatera') }} - Admin</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="bg-gray-100 text-gray-900 font-sans">
    <!-- Admin Navbar -->
    <nav class="bg-gradient-to-r from-gray-900 to-gray-800 text-white border-b">
      <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="/admin/dashboard" class="font-bold text-xl">⚙️ Eatera Admin</a>
        <div class="space-x-4 flex items-center">
          <a href="/admin/dashboard" class="text-sm hover:text-rose-300">Dashboard</a>
          <a href="/admin/foods" class="text-sm hover:text-rose-300">Kelola Makanan</a>
          <a href="/admin/komunitas" class="text-sm hover:text-rose-300">Komunitas</a>
          <a href="/admin/artikel" class="text-sm hover:text-rose-300">Artikel</a>
        </div>
        <div id="adminLogout" class="flex items-center gap-2">
          <button id="logoutBtn" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">Logout</button>
        </div>
      </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6">
      @yield('content')
    </main>

    <script>
    // Check authentication and admin role
    async function checkAdminAuth() {
      const token = localStorage.getItem('auth_token');
      
      if (!token) {
        console.warn('No auth token found, redirecting to login');
        window.location.href = '/login';
        return;
      }
      
      try {
        // Set Authorization header
        if (window.axios) {
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
        
        // Fetch user to check role
        const response = await fetch('/api/user', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          console.error('Failed to fetch user:', response.status);
          localStorage.removeItem('auth_token');
          window.location.href = '/login';
          return;
        }
        
        const user = await response.json();
        
        if (user.role !== 'admin') {
          console.error('User is not admin:', user.role);
          window.location.href = '/';
          return;
        }
        
        console.log('Admin auth verified:', user.name);
      } catch (err) {
        console.error('Auth check error:', err);
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
      }
    }
    
    // Check auth on page load
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', checkAdminAuth);
    } else {
      checkAdminAuth();
    }
    
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

    async function logout() {
      await csrf();
      await new Promise(resolve => setTimeout(resolve, 100));
      return window.axios.post('/logout');
    }

    document.getElementById('logoutBtn').addEventListener('click', async () => {
      try {
        console.log('Logging out...');
        await logout();
        console.log('Logout successful');
        localStorage.removeItem('auth_token');
        window.location.href = '/';
      } catch (err) {
        console.error('Logout error:', err);
        alert('Logout failed: ' + (err.response?.data?.message || err.message));
      }
    });
    </script>
  </body>
</html>

