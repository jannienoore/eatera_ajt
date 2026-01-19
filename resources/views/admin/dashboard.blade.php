@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-8">📊 Admin Dashboard</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
  <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow border-l-4 border-blue-500">
    <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Foods</h3>
    <p id="totalFoods" class="text-3xl font-bold text-blue-600">--</p>
    <p class="text-xs text-gray-500 mt-2">Makanan tersedia</p>
  </div>

  <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow border-l-4 border-green-500">
    <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Users</h3>
    <p id="totalUsers" class="text-3xl font-bold text-green-600">--</p>
    <p class="text-xs text-gray-500 mt-2">Pengguna Calomate</p>
  </div>

  <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg shadow border-l-4 border-orange-500">
    <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Entries</h3>
    <p id="totalEntries" class="text-3xl font-bold text-orange-600">--</p>
    <p class="text-xs text-gray-500 mt-2">Food journal entries</p>
  </div>

  <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow border-l-4 border-purple-500">
    <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Admins</h3>
    <p id="totalAdmins" class="text-3xl font-bold text-purple-600">--</p>
    <p class="text-xs text-gray-500 mt-2">Admin accounts</p>
  </div>
</div>

<!-- Quick Stats Info -->
<div class="bg-white p-6 rounded-lg shadow">
  <h2 class="text-2xl font-bold mb-4">📈 Statistik Sistem</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="border-l-4 border-blue-500 pl-4">
      <h3 class="font-semibold text-gray-700 mb-2">👥 User Management</h3>
      <p class="text-gray-600 text-sm">Total <strong id="statsText1">--</strong> pengguna aktif terdaftar di sistem Calomate.</p>
    </div>
    <div class="border-l-4 border-green-500 pl-4">
      <h3 class="font-semibold text-gray-700 mb-2">🍽️ Food Database</h3>
      <p class="text-gray-600 text-sm"><strong id="statsText2">--</strong> makanan tersedia dalam database untuk pencarian dan import.</p>
    </div>
  </div>
</div>

<script>
async function load() {
  try {
    console.log('Checking admin authentication...');
    
    // Check if user is logged in and is admin
    const userRes = await window.axios.get('/user');
    const user = userRes.data;
    console.log('User:', user);
    
    if (!user || !user.id) {
      console.log('Not logged in, redirecting to login');
      window.location.href = '/login';
      return;
    }
    
    if (user.role !== 'admin') {
      console.log('Not admin, redirecting to dashboard');
      window.location.href = '/dashboard';
      return;
    }

    // Load admin statistics
    console.log('Loading admin statistics...');
    const statsRes = await window.axios.get('/admin/stats');
    const stats = statsRes.data;
    console.log('Stats:', stats);

    document.getElementById('totalFoods').textContent = stats.total_foods || 0;
    document.getElementById('totalUsers').textContent = stats.total_users || 0;
    document.getElementById('totalEntries').textContent = stats.total_journal_entries || 0;
    document.getElementById('totalAdmins').textContent = stats.total_admins || 0;

    // Update stats text
    document.getElementById('statsText1').textContent = stats.total_users || 0;
    document.getElementById('statsText2').textContent = stats.total_foods || 0;

  } catch (err) {
    console.error('Admin dashboard error:', err);
    if (err.response?.status === 403) {
      window.location.href = '/dashboard';
    } else if (err.response?.status === 401) {
      window.location.href = '/login';
    }
  }
}

// Wait for axios to be available
function waitForAxios() {
  if (window.axios) {
    console.log('Axios is ready, loading dashboard...');
    load();
  } else {
    console.log('Waiting for axios...');
    setTimeout(waitForAxios, 100);
  }
}

waitForAxios();
</script>

@endsection
