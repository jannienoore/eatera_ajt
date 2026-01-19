@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
  <h1 class="text-4xl font-bold mb-8">📊 Riwayat Nutrisi</h1>
  
  <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-4"></div>
  <div id="successMsg" class="hidden bg-green-100 text-green-700 p-4 rounded mb-4"></div>

  <!-- Tab Navigation -->
  <div class="flex border-b border-gray-200 mb-8">
    <button id="tabDaily" class="px-6 py-3 text-lg font-semibold border-b-2 border-rose-400 text-rose-400">
      📅 Harian
    </button>
    <button id="tabWeekly" class="px-6 py-3 text-lg font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900">
      📈 Mingguan
    </button>
    <button id="tabAllTime" class="px-6 py-3 text-lg font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900">
      📊 Semua Waktu
    </button>
  </div>

  <!-- Daily Tab -->
  <div id="dailyTab" class="tab-content">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Date Picker -->
      <div class="bg-white p-6 rounded-lg shadow">
        <label class="block text-sm font-semibold mb-2">Pilih Tanggal</label>
        <input type="date" id="dailyDatePicker" class="w-full border rounded px-3 py-2">
      </div>

      <!-- Summary Stats -->
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Total Kalori</div>
        <div class="text-4xl font-bold text-blue-600" id="dailyTotalCal">--</div>
        <div class="text-xs text-gray-500 mt-2" id="dailyProgress">--</div>
      </div>

      <!-- Target Info -->
      <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Target Kalori</div>
        <div class="text-4xl font-bold text-green-600" id="dailyTarget">--</div>
        <div class="text-xs text-gray-500 mt-2" id="dailyDiff">--</div>
      </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h2 class="text-2xl font-bold mb-6">Breakdown Nutrisi</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-red-50 to-orange-50 p-4 rounded border border-red-200">
          <div class="text-sm font-semibold text-gray-700 mb-2">🔥 Protein</div>
          <div class="text-3xl font-bold text-orange-600" id="dailyProtein">--</div>
          <div class="text-xs text-gray-600 mt-1">gram</div>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 p-4 rounded border border-yellow-200">
          <div class="text-sm font-semibold text-gray-700 mb-2">🧈 Lemak</div>
          <div class="text-3xl font-bold text-amber-600" id="dailyFat">--</div>
          <div class="text-xs text-gray-600 mt-1">gram</div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded border border-green-200">
          <div class="text-sm font-semibold text-gray-700 mb-2">🌾 Karbo</div>
          <div class="text-3xl font-bold text-emerald-600" id="dailyCarbs">--</div>
          <div class="text-xs text-gray-600 mt-1">gram</div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-4 rounded border border-purple-200">
          <div class="text-sm font-semibold text-gray-700 mb-2">🌱 Serat</div>
          <div class="text-3xl font-bold text-violet-600" id="dailyFiber">--</div>
          <div class="text-xs text-gray-600 mt-1">gram</div>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded border border-blue-200">
          <div class="text-sm font-semibold text-gray-700 mb-2">⏺️ Asupan</div>
          <div class="text-3xl font-bold text-indigo-600" id="dailyIntake">--</div>
          <div class="text-xs text-gray-600 mt-1">%</div>
        </div>
      </div>
    </div>

    <!-- Meals Breakdown -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-2xl font-bold mb-6">Distribusi Makanan</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="border-l-4 border-orange-400 bg-orange-50 p-4 rounded">
          <div class="font-semibold text-sm text-gray-700 mb-2">🌅 Sarapan</div>
          <div class="text-2xl font-bold text-orange-600" id="dailyBreakfastCal">0</div>
          <div class="text-xs text-gray-600 mt-1" id="dailyBreakfastPercent">0%</div>
        </div>
        <div class="border-l-4 border-yellow-400 bg-yellow-50 p-4 rounded">
          <div class="font-semibold text-sm text-gray-700 mb-2">☀️ Makan Siang</div>
          <div class="text-2xl font-bold text-yellow-600" id="dailyLunchCal">0</div>
          <div class="text-xs text-gray-600 mt-1" id="dailyLunchPercent">0%</div>
        </div>
        <div class="border-l-4 border-purple-400 bg-purple-50 p-4 rounded">
          <div class="font-semibold text-sm text-gray-700 mb-2">🌙 Makan Malam</div>
          <div class="text-2xl font-bold text-purple-600" id="dailyDinnerCal">0</div>
          <div class="text-xs text-gray-600 mt-1" id="dailyDinnerPercent">0%</div>
        </div>
        <div class="border-l-4 border-pink-400 bg-pink-50 p-4 rounded">
          <div class="font-semibold text-sm text-gray-700 mb-2">🍿 Snack</div>
          <div class="text-2xl font-bold text-pink-600" id="dailySnackCal">0</div>
          <div class="text-xs text-gray-600 mt-1" id="dailySnackPercent">0%</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Weekly Tab -->
  <div id="weeklyTab" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Week Selector -->
      <div class="bg-white p-6 rounded-lg shadow">
        <label class="block text-sm font-semibold mb-2">Minggu Dimulai</label>
        <input type="date" id="weeklyDatePicker" class="w-full border rounded px-3 py-2">
      </div>

      <!-- Weekly Average -->
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Rata-rata Harian</div>
        <div class="text-4xl font-bold text-blue-600" id="weeklyAverage">--</div>
        <div class="text-xs text-gray-500 mt-2">kcal per hari</div>
      </div>

      <!-- Weekly Target -->
      <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Total Minggu</div>
        <div class="text-4xl font-bold text-green-600" id="weeklyTotal">--</div>
        <div class="text-xs text-gray-500 mt-2">kcal (7 hari)</div>
      </div>
    </div>

    <!-- Weekly Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-2xl font-bold mb-6">📈 Tren Kalori Mingguan</h2>
      <div id="weeklyChart" class="space-y-3">
        <div class="text-center text-gray-500 py-8">Loading chart...</div>
      </div>
    </div>
  </div>

  <!-- All-Time Tab -->
  <div id="allTimeTab" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Total Entri</div>
        <div class="text-4xl font-bold text-blue-600" id="allTimeEntries">--</div>
        <div class="text-xs text-gray-500 mt-2">makanan</div>
      </div>

      <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Hari Tercatat</div>
        <div class="text-4xl font-bold text-green-600" id="allTimeDays">--</div>
        <div class="text-xs text-gray-500 mt-2">hari</div>
      </div>

      <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Rata-rata Harian</div>
        <div class="text-4xl font-bold text-orange-600" id="allTimeAverage">--</div>
        <div class="text-xs text-gray-500 mt-2">kcal</div>
      </div>

      <div class="bg-gradient-to-r from-pink-50 to-rose-50 p-6 rounded-lg shadow">
        <div class="text-sm text-gray-600 mb-2">Konsistensi</div>
        <div class="text-4xl font-bold text-rose-600" id="allTimeConsistency">--</div>
        <div class="text-xs text-gray-500 mt-2">pencatatan</div>
      </div>
    </div>

    <!-- All-Time Table -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-2xl font-bold mb-6">📋 Riwayat Lengkap</h2>
      
      <!-- Search & Filter -->
      <div class="mb-4">
        <input type="text" id="allTimeSearch" placeholder="🔍 Cari tanggal (misal: 2025-12-25)..." class="w-full border rounded px-3 py-2">
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b">
            <tr>
              <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
              <th class="text-right px-4 py-3 font-semibold">Kalori</th>
              <th class="text-right px-4 py-3 font-semibold">% Target</th>
              <th class="text-center px-4 py-3 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody id="allTimeTable">
            <tr><td colspan="4" class="text-center py-8 text-gray-500">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const errorMsg = document.getElementById('errorMsg');
const successMsg = document.getElementById('successMsg');

// Tab Management
const tabDaily = document.getElementById('tabDaily');
const tabWeekly = document.getElementById('tabWeekly');
const tabAllTime = document.getElementById('tabAllTime');
const tabContents = {
  daily: document.getElementById('dailyTab'),
  weekly: document.getElementById('weeklyTab'),
  allTime: document.getElementById('allTimeTab')
};

function showTab(tab) {
  Object.values(tabContents).forEach(content => content.classList.add('hidden'));
  tabContents[tab].classList.remove('hidden');
  
  [tabDaily, tabWeekly, tabAllTime].forEach(btn => {
    btn.classList.remove('border-rose-400', 'text-rose-400');
    btn.classList.add('border-transparent', 'text-gray-600');
  });
  
  if (tab === 'daily') tabDaily.classList.add('border-rose-400', 'text-rose-400');
  else if (tab === 'weekly') tabWeekly.classList.add('border-rose-400', 'text-rose-400');
  else if (tab === 'allTime') tabAllTime.classList.add('border-rose-400', 'text-rose-400');
}

tabDaily.addEventListener('click', () => { showTab('daily'); loadDaily(); });
tabWeekly.addEventListener('click', () => { showTab('weekly'); loadWeekly(); });
tabAllTime.addEventListener('click', () => { showTab('allTime'); loadAllTime(); });

// Helper function to get today's date string (YYYY-MM-DD)
function getTodayDateString() {
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// Helper function to get start of week date string (YYYY-MM-DD)
function getStartOfWeekDateString() {
  const now = new Date();
  const dayOfWeek = now.getDay();
  const startOfWeek = new Date(now);
  startOfWeek.setDate(now.getDate() - dayOfWeek);
  const year = startOfWeek.getFullYear();
  const month = String(startOfWeek.getMonth() + 1).padStart(2, '0');
  const day = String(startOfWeek.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// ===== DAILY TAB =====
const dailyDatePicker = document.getElementById('dailyDatePicker');
dailyDatePicker.value = getTodayDateString();

dailyDatePicker.addEventListener('change', loadDaily);

async function loadDaily() {
  try {
    const date = dailyDatePicker.value;
    console.log('Loading daily:', date);
    const res = await window.axios.get('/dashboard/daily', { params: { date } });
    const data = res.data;
    console.log('Daily data:', data);

    // Summary
    document.getElementById('dailyTotalCal').textContent = Math.round(data.summary.calories).toLocaleString();
    document.getElementById('dailyTarget').textContent = data.target_calories.toLocaleString();
    document.getElementById('dailyProgress').textContent = `${data.progress_percent}% dari target`;
    const diff = Math.round(data.summary.calories - data.target_calories);
    document.getElementById('dailyDiff').textContent = (diff > 0 ? '+' : '') + diff + ' kcal';

    // Nutrition
    document.getElementById('dailyProtein').textContent = Math.round(data.summary.protein);
    document.getElementById('dailyFat').textContent = Math.round(data.summary.fat);
    document.getElementById('dailyCarbs').textContent = Math.round(data.summary.carbohydrates);
    document.getElementById('dailyFiber').textContent = Math.round(data.summary.fiber);
    document.getElementById('dailyIntake').textContent = Math.round(data.progress_percent) + '%';

    // Meals
    const totalCal = data.summary.calories;
    ['breakfast', 'lunch', 'dinner', 'snack'].forEach(meal => {
      const cal = data.meals[meal]?.calories || 0;
      const percent = totalCal > 0 ? ((cal / totalCal) * 100).toFixed(1) : 0;
      document.getElementById(`daily${meal.charAt(0).toUpperCase() + meal.slice(1)}Cal`).textContent = Math.round(cal);
      document.getElementById(`daily${meal.charAt(0).toUpperCase() + meal.slice(1)}Percent`).textContent = percent + '%';
    });
  } catch (err) {
    console.error('Load daily error:', err);
    if (err.response?.status === 401) {
      window.location.href = '/login';
    }
  }
}

// ===== WEEKLY TAB =====
const weeklyDatePicker = document.getElementById('weeklyDatePicker');
weeklyDatePicker.value = getStartOfWeekDateString();

weeklyDatePicker.addEventListener('change', loadWeekly);

async function loadWeekly() {
  try {
    const start = weeklyDatePicker.value;
    console.log('Loading weekly:', start);
    const res = await window.axios.get('/dashboard/weekly', { params: { start } });
    const data = res.data;
    console.log('Weekly data:', data);

    document.getElementById('weeklyAverage').textContent = Math.round(data.average_calories).toLocaleString();
    document.getElementById('weeklyTotal').textContent = (Math.round(data.average_calories) * 7).toLocaleString();

    // Build chart
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const maxCal = Math.max(...data.daily.map(d => d.calories), data.target_calories) * 1.1;
    
    let chartHTML = '';
    data.daily.forEach(day => {
      const percent = (day.calories / maxCal) * 100;
      const targetPercent = (data.target_calories / maxCal) * 100;
      const date = new Date(day.date);
      const dayName = dayNames[date.getDay()];
      
      chartHTML += `
        <div class="flex items-end gap-3">
          <div class="w-20 text-xs text-gray-600 font-semibold">
            <div>${dayName}</div>
            <div class="text-gray-500">${day.date}</div>
          </div>
          <div class="flex-1 flex gap-1 items-end h-24">
            <div class="flex-1 bg-blue-300 rounded-t" style="height: ${percent}%"></div>
            <div class="w-0.5 bg-red-500 rounded" style="height: ${targetPercent}%; opacity: 0.7"></div>
          </div>
          <div class="w-16 text-right">
            <div class="font-bold text-blue-600">${Math.round(day.calories)}</div>
            <div class="text-xs text-gray-500">${((day.calories / data.target_calories) * 100).toFixed(0)}%</div>
          </div>
        </div>
      `;
    });
    
    chartHTML += `<div class="flex items-center gap-3 mt-6 pt-6 border-t text-xs">
      <div class="flex items-center gap-2">
        <div class="w-3 h-3 bg-blue-300 rounded"></div>
        <span>Asupan Kalori</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-0.5 h-3 bg-red-500"></div>
        <span>Target Kalori</span>
      </div>
    </div>`;

    document.getElementById('weeklyChart').innerHTML = chartHTML;
  } catch (err) {
    console.error('Load weekly error:', err);
  }
}

// ===== ALL-TIME TAB =====
let allTimeData = [];

async function loadAllTime() {
  try {
    console.log('Loading all time...');
    const res = await window.axios.get('/journal/all');
    allTimeData = res.data;
    console.log('All time data:', allTimeData);

    const entryCount = allTimeData.length;
    const uniqueDays = new Set(allTimeData.map(entry => entry.eaten_at)).size;
    
    // Get daily stats for all entries
    const dailyStats = {};
    allTimeData.forEach(entry => {
      if (!dailyStats[entry.eaten_at]) {
        dailyStats[entry.eaten_at] = { calories: 0 };
      }
      // Kalori per 100g * quantity(gram) / 100
      dailyStats[entry.eaten_at].calories += (entry.food.calories * entry.quantity) / 100;
    });

    const avgDaily = Object.values(dailyStats).length > 0 
      ? Object.values(dailyStats).reduce((sum, d) => sum + d.calories, 0) / Object.values(dailyStats).length
      : 0;

    document.getElementById('allTimeEntries').textContent = entryCount;
    document.getElementById('allTimeDays').textContent = uniqueDays;
    document.getElementById('allTimeAverage').textContent = Math.round(avgDaily).toLocaleString();
    document.getElementById('allTimeConsistency').textContent = uniqueDays + ' hari';

    // Build table
    renderAllTimeTable();
  } catch (err) {
    console.error('Load all time error:', err);
  }
}

function formatDateID(dateString) {
  if (!dateString) return 'Tanggal Tidak Valid';
  
  // Handle both YYYY-MM-DD and ISO 8601 formats (with or without time/timezone)
  let date;
  
  if (dateString.includes('T')) {
    // Already ISO 8601 format
    date = new Date(dateString);
  } else if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
    // Just date format YYYY-MM-DD, add time to avoid timezone issues
    date = new Date(dateString + 'T00:00:00');
  } else {
    date = new Date(dateString);
  }
  
  if (isNaN(date.getTime())) return 'Tanggal Tidak Valid';
  
  return date.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

function renderAllTimeTable(searchTerm = '') {
  const dailyStats = {};
  allTimeData.forEach(entry => {
    if (!dailyStats[entry.eaten_at]) {
      dailyStats[entry.eaten_at] = { calories: 0 };
    }
    // Kalori per 100g * quantity(gram) / 100
    dailyStats[entry.eaten_at].calories += (entry.food.calories * entry.quantity) / 100;
  });

  let rows = Object.entries(dailyStats)
    .sort(([dateA], [dateB]) => dateB.localeCompare(dateA))
    .filter(([date]) => !searchTerm || date.includes(searchTerm));

  if (rows.length === 0) {
    document.getElementById('allTimeTable').innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-500">Tidak ada data</td></tr>';
    return;
  }

  // Fetch target calories once
  window.axios.get('/profile').then(res => {
    const targetCal = res.data.target_calories || 2000;
    
    const tableHTML = rows.map(([date, stats]) => {
      const percent = ((stats.calories / targetCal) * 100).toFixed(1);
      const status = percent >= 80 && percent <= 110 ? '✅ On Target' : percent < 80 ? '⬇️ Under' : '⬆️ Over';
      const statusColor = percent >= 80 && percent <= 110 ? 'text-green-600' : percent < 80 ? 'text-blue-600' : 'text-orange-600';
      const formattedDate = formatDateID(date);
      
      return `
        <tr class="border-b hover:bg-gray-50">
          <td class="px-4 py-3 font-semibold">${formattedDate}<br><small class="text-gray-500 font-normal text-xs">${date}</small></td>
          <td class="text-right px-4 py-3">${Math.round(stats.calories).toLocaleString()} kcal</td>
          <td class="text-right px-4 py-3">${percent}%</td>
          <td class="text-center px-4 py-3 ${statusColor}">${status}</td>
        </tr>
      `;
    }).join('');

    document.getElementById('allTimeTable').innerHTML = tableHTML;
  });
}

document.getElementById('allTimeSearch').addEventListener('input', (e) => {
  renderAllTimeTable(e.target.value);
});

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
    console.log(`History: Next refresh in ${(msUntilMidnight / 1000 / 60).toFixed(1)} minutes`);
    
    setTimeout(() => {
      console.log('History: Midnight reached, refreshing data...');
      // Reset date pickers to today
      dailyDatePicker.value = getTodayDateString();
      weeklyDatePicker.value = getStartOfWeekDateString();
      // Reload current tab
      loadDaily();
      scheduleRefresh(); // Schedule next refresh
    }, msUntilMidnight);
  }

  scheduleRefresh();
}

// Wait for axios
function waitForAxios() {
  if (window.axios) {
    loadDaily();
    setupMidnightRefresh();
  } else {
    setTimeout(waitForAxios, 100);
  }
}

waitForAxios();
</script>
@endsection
