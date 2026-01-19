<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name','Eatera') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="bg-gray-50 text-gray-900 font-sans">
    <!-- Navbar for Calomate (User) - hidden on auth pages -->
    <nav class="bg-white border-b" id="mainNav">
      <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="/" class="font-bold text-xl text-rose-400">Eatera</a>
        <div class="space-x-4 flex items-center">
          <a id="homeMenu" href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
          <a href="/dashboard" class="text-sm text-gray-600 hover:text-gray-900">CaloHome</a>
          <a href="/food-journal" class="text-sm text-gray-600 hover:text-gray-900">CaloTrack</a>
          <a href="/rekomendasi" class="text-sm text-gray-600 hover:text-gray-900">CaloPick</a>
          <a href="/nutrition-history" class="text-sm text-gray-600 hover:text-gray-900">CaloHistory</a>
          <a href="/komunitas" class="text-sm text-gray-600 hover:text-gray-900">CaloCircle</a>
        </div>
        <div id="profileMenu" class="flex items-center gap-3">
          <div class="relative group">
            <button class="text-sm text-gray-600 hover:text-gray-900">👤 Profile</button>
            <div class="hidden group-hover:block absolute right-0 bg-white border rounded shadow-lg z-10">
              <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Profile</a>
              <button id="logoutBtn" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-[100]"></div>

    <!-- Floating Chatbot Widget -->
    @include('components.chatbot-widget')

    <main class="max-w-6xl mx-auto p-6">
      @yield('content')
    </main>

    <script>
    // Hide navbar on login/register pages
    const currentPath = window.location.pathname;
    const mainNav = document.getElementById('mainNav');
    if (currentPath === '/login' || currentPath === '/register') {
      if (mainNav) mainNav.style.display = 'none';
    }

    // Hide Home menu if user is logged in
    const homeMenu = document.getElementById('homeMenu');
    const token = localStorage.getItem('auth_token');
    if (token && homeMenu) {
      homeMenu.style.display = 'none';
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
      try {
        // Ensure we have the token set in the header
        const authToken = localStorage.getItem('auth_token');
        if (authToken) {
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
        }
        
        // Make logout request
        const response = await window.axios.post('/logout');
        console.log('Logout API response:', response.data);
        return response;
      } catch (err) {
        console.error('Logout request error:', err);
        throw err;
      }
    }

    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
          console.log('Starting logout process...');
          
          // Get CSRF cookie first
          await csrf();
          await new Promise(resolve => setTimeout(resolve, 100));
          
          console.log('Sending logout request to /api/logout');
          await logout();
          
          console.log('Logout successful, clearing token and redirecting');
          localStorage.removeItem('auth_token');
          delete window.axios.defaults.headers.common['Authorization'];
          window.location.href = '/';
        } catch (err) {
          console.error('Logout error:', err);
          // Even if the API call fails, clear the local token and redirect
          localStorage.removeItem('auth_token');
          delete window.axios.defaults.headers.common['Authorization'];
          window.location.href = '/';
        }
      });
    }
    </script>
  </body>
</html>
