@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
  <h1 class="text-4xl font-bold mb-8">Dashboard Harian</h1>
  
  <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-6"></div>
  
  <!-- Warning: Exceeded Calorie Target -->
  <div id="exceededWarning" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
    <div class="flex items-start gap-3">
      <div class="text-2xl">⚠️</div>
      <div>
        <h3 class="font-bold text-red-700 mb-1">Target Kalori Terlampaui!</h3>
        <p class="text-red-600 text-sm">Anda sudah melebihi target kalori harian. Hindari input makanan tambahan untuk hari ini.</p>
      </div>
    </div>
  </div>
  
  <!-- Main Stats Row -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Target Kalori -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="text-gray-600 text-sm font-semibold mb-2">Target Harian</div>
      <div class="text-3xl font-bold text-blue-600" id="targetCalories">--</div>
      <div class="text-xs text-gray-500 mt-2">kcal</div>
    </div>
    
    <!-- Kalori Terkonsumsi -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="text-gray-600 text-sm font-semibold mb-2">Terkonsumsi</div>
      <div class="text-3xl font-bold text-green-600" id="consumedCalories">--</div>
      <div class="text-xs text-gray-500 mt-2">kcal</div>
    </div>
    
    <!-- Kalori Sisa -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="text-gray-600 text-sm font-semibold mb-2">Sisa</div>
      <div class="text-3xl font-bold text-orange-600" id="remainingCalories">--</div>
      <div class="text-xs text-gray-500 mt-2">kcal</div>
    </div>
    
    <!-- Progress Persen -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="text-gray-600 text-sm font-semibold mb-2">Progress</div>
      <div class="text-3xl font-bold text-purple-600" id="progressPercent">--</div>
      <div class="text-xs text-gray-500 mt-2">%</div>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="bg-white p-6 rounded-lg shadow mb-8">
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-lg font-bold">Progress Kalori Harian</h2>
      <span class="text-sm text-gray-600"><span id="progressSpan">0</span> / <span id="targetSpan">0</span> kcal</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
      <div id="progressBar" class="bg-gradient-to-r from-green-400 to-blue-500 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
    </div>
  </div>

  <!-- Nutrisi Breakdown -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Protein -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-gray-700">Protein</h3>
        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">⚡</span>
      </div>
      <div class="text-2xl font-bold text-blue-600" id="proteinVal">--</div>
      <div class="text-xs text-gray-500 mt-2">gram</div>
    </div>
    
    <!-- Lemak -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-gray-700">Lemak</h3>
        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">🔥</span>
      </div>
      <div class="text-2xl font-bold text-red-600" id="fatVal">--</div>
      <div class="text-xs text-gray-500 mt-2">gram</div>
    </div>
    
    <!-- Karbohidrat -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-gray-700">Karbohidrat</h3>
        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">🌾</span>
      </div>
      <div class="text-2xl font-bold text-yellow-600" id="carbsVal">--</div>
      <div class="text-xs text-gray-500 mt-2">gram</div>
    </div>
    
    <!-- Serat -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-gray-700">Serat</h3>
        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">🌱</span>
      </div>
      <div class="text-2xl font-bold text-green-600" id="fiberVal">--</div>
      <div class="text-xs text-gray-500 mt-2">gram</div>
    </div>
  </div>

  <!-- Meals Breakdown -->
  <div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Kalori per Waktu Makan</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Breakfast -->
      <div class="border-l-4 border-orange-400 p-4 bg-orange-50 rounded">
        <div class="font-semibold text-gray-700 mb-2">🌅 Sarapan</div>
        <div class="text-2xl font-bold text-orange-600" id="breakfastCal">--</div>
        <div class="text-xs text-gray-600 mt-2">kcal</div>
      </div>
      
      <!-- Lunch -->
      <div class="border-l-4 border-yellow-400 p-4 bg-yellow-50 rounded">
        <div class="font-semibold text-gray-700 mb-2">☀️ Makan Siang</div>
        <div class="text-2xl font-bold text-yellow-600" id="lunchCal">--</div>
        <div class="text-xs text-gray-600 mt-2">kcal</div>
      </div>
      
      <!-- Dinner -->
      <div class="border-l-4 border-purple-400 p-4 bg-purple-50 rounded">
        <div class="font-semibold text-gray-700 mb-2">🌙 Makan Malam</div>
        <div class="text-2xl font-bold text-purple-600" id="dinnerCal">--</div>
        <div class="text-xs text-gray-600 mt-2">kcal</div>
      </div>
      
      <!-- Snack -->
      <div class="border-l-4 border-pink-400 p-4 bg-pink-50 rounded">
        <div class="font-semibold text-gray-700 mb-2">🍿 Snack</div>
        <div class="text-2xl font-bold text-pink-600" id="snackCal">--</div>
        <div class="text-xs text-gray-600 mt-2">kcal</div>
      </div>
    </div>
  </div>

  <!-- Debug/Info -->
  <div class="mt-8 text-xs text-gray-500 text-center">
    <p>Data diupdate otomatis dari asupan makanan Anda</p>
    <p id="dateDisplay">--</p>
  </div>
</div>

<script>
async function loadDashboard() {
  const errorMsg = document.getElementById('errorMsg');
  
  try {
    console.log('Loading dashboard data...');
    
    // Check if user is logged in
    const userRes = await window.axios.get('/user');
    console.log('User:', userRes.data);
    
    if (!userRes.data || !userRes.data.id) {
      window.location.href = '/login';
      return;
    }
    
    // Load dashboard daily data
    const res = await window.axios.get('/dashboard/daily');
    const data = res.data;
    console.log('Dashboard data:', data);
    
    // Update main stats
    const target = data.target_calories || 2000;
    const consumed = data.summary.calories || 0;
    const remaining = Math.max(0, target - consumed);
    const progress = data.progress_percent || 0;
    const isExceeded = data.is_exceeded || false;
    
    document.getElementById('targetCalories').textContent = target.toLocaleString();
    document.getElementById('consumedCalories').textContent = consumed.toLocaleString();
    document.getElementById('remainingCalories').textContent = remaining.toLocaleString();
    document.getElementById('progressPercent').textContent = progress.toFixed(1);
    document.getElementById('progressSpan').textContent = consumed.toFixed(0);
    document.getElementById('targetSpan').textContent = target;
    
    // Show/hide exceeded warning
    const exceededWarning = document.getElementById('exceededWarning');
    if (isExceeded) {
      exceededWarning.classList.remove('hidden');
    } else {
      exceededWarning.classList.add('hidden');
    }
    
    // Update progress bar
    const progressWidth = Math.min(100, (consumed / target) * 100);
    document.getElementById('progressBar').style.width = progressWidth + '%';
    
    // Update nutrients
    document.getElementById('proteinVal').textContent = data.summary.protein.toFixed(1);
    document.getElementById('fatVal').textContent = data.summary.fat.toFixed(1);
    document.getElementById('carbsVal').textContent = data.summary.carbohydrates.toFixed(1);
    document.getElementById('fiberVal').textContent = data.summary.fiber.toFixed(1);
    
    // Update meals
    document.getElementById('breakfastCal').textContent = data.meals.breakfast?.calories.toFixed(0) || 0;
    document.getElementById('lunchCal').textContent = data.meals.lunch?.calories.toFixed(0) || 0;
    document.getElementById('dinnerCal').textContent = data.meals.dinner?.calories.toFixed(0) || 0;
    document.getElementById('snackCal').textContent = data.meals.snack?.calories.toFixed(0) || 0;
    
    // Update date
    document.getElementById('dateDisplay').textContent = new Date(data.date).toLocaleDateString('id-ID', { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    });
    
  } catch (err) {
    console.error('Dashboard error:', err);
    
    if (err.response?.status === 401) {
      window.location.href = '/login';
    } else {
      const msg = err.response?.data?.message || err.message || 'Gagal memuat dashboard';
      errorMsg.textContent = msg;
      errorMsg.classList.remove('hidden');
    }
  }
}

// Auto-refresh at midnight
function setupMidnightRefresh() {
  function calculateMsUntilMidnight() {
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0);
    return tomorrow.getTime() - now.getTime();
  }

  function scheduleRefresh() {
    const msUntilMidnight = calculateMsUntilMidnight();
    console.log(`Dashboard: Next refresh in ${(msUntilMidnight / 1000 / 60).toFixed(1)} minutes`);
    
    setTimeout(() => {
      console.log('Dashboard: Midnight reached, refreshing data...');
      loadDashboard();
      scheduleRefresh(); // Schedule next refresh
    }, msUntilMidnight);
  }

  scheduleRefresh();
}

// Wait for axios then load
function waitForAxios() {
  if (window.axios) {
    console.log('Axios ready, loading dashboard...');
    loadDashboard();
    setupMidnightRefresh();
  } else {
    setTimeout(waitForAxios, 100);
  }
}

waitForAxios();
</script>
@endsection
