@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-4xl font-bold mb-2">🍽️ Rekomendasi Menu Harian</h1>
    <p class="text-gray-600">Menu pilihan berdasarkan profil dan target diet Anda</p>
  </div>

  <!-- Login Prompt -->
  <div id="loginPrompt" class="bg-white p-8 rounded-lg shadow text-center mb-8 hidden">
    <h2 class="text-2xl font-bold mb-4">Belum Login</h2>
    <p class="text-gray-600 mb-6">Silakan login untuk melihat rekomendasi menu yang dipersonalisasi</p>
    <div class="flex gap-4 justify-center">
      <a href="/login" class="bg-rose-400 text-white px-6 py-2 rounded font-semibold hover:bg-rose-500">Login</a>
      <a href="/register" class="bg-blue-400 text-white px-6 py-2 rounded font-semibold hover:bg-blue-500">Register</a>
    </div>
  </div>

  <!-- Content for authenticated users -->
  <div id="recommendationContent" class="hidden">
    <!-- Alert Messages -->
    <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-6"></div>
    <div id="successMsg" class="hidden bg-green-100 text-green-700 p-4 rounded mb-6"></div>

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

    <!-- Profile Summary Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow border border-blue-200">
        <div class="text-sm text-gray-600 font-semibold mb-1">Target Diet</div>
        <div class="text-2xl font-bold text-blue-600" id="dietGoalDisplay">--</div>
      </div>
      <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow border border-purple-200">
        <div class="text-sm text-gray-600 font-semibold mb-3">Target Kalori Harian</div>
        <div class="flex items-baseline gap-2 mb-3">
          <span id="consumedCaloriesDisplay" class="text-2xl font-bold text-purple-600">0</span>
          <span class="text-gray-500">/</span>
          <span id="targetCaloriesDisplay" class="text-xl font-bold text-gray-700">--</span>
          <span class="text-xs text-gray-500">kcal</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
          <div id="calorieProgressBar" class="bg-gradient-to-r from-purple-400 to-purple-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Progress: <span id="calorieProgressPercent">0</span>%</p>
      </div>
      <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow border border-green-200">
        <div class="text-sm text-gray-600 font-semibold mb-3">Sisa Kalori</div>
        <div class="text-2xl font-bold text-green-600 mb-3" id="remainingCalories">0 kcal</div>
        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
          <div id="remainingProgressBar" class="bg-gradient-to-r from-green-400 to-green-600 h-full rounded-full transition-all duration-300" style="width: 100%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Sisa: <span id="remainingPercent">100</span>%</p>
      </div>
      <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg shadow border border-orange-200">
        <div class="text-sm text-gray-600 font-semibold mb-1">Tanggal</div>
        <div class="text-xl font-bold text-orange-600" id="dateDisplay">--</div>
      </div>
    </div>

    <!-- Recommendations Grid -->
    <div class="mb-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Menu Pilihan Hari Ini</h2>
        <button id="refreshBtn" class="bg-blue-500 text-white px-4 py-2 rounded font-semibold hover:bg-blue-600 flex items-center gap-2">
          🔄 Refresh
        </button>
      </div>

      <!-- Loading State -->
      <div id="loadingState" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-600">Memuat rekomendasi...</p>
      </div>

      <!-- Recommendations Cards -->
      <div id="recommendationsGrid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Cards will be inserted here -->
      </div>

      <!-- Empty State -->
      <div id="emptyState" class="hidden bg-yellow-50 border border-yellow-200 p-8 rounded-lg text-center">
        <p class="text-yellow-700 font-semibold">Tidak ada rekomendasi tersedia</p>
        <p class="text-yellow-600 text-sm mt-2">Silakan lengkapi profil Anda terlebih dahulu</p>
      </div>
    </div>

    <!-- Food Details Modal -->
    <div id="foodModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-rose-400 to-rose-500 p-6 text-white flex justify-between items-start">
          <div>
            <h2 class="text-2xl font-bold" id="modalFoodName">--</h2>
            <p class="text-rose-100 text-sm mt-1" id="modalFoodSource">--</p>
          </div>
          <button onclick="closeFoodModal()" class="text-white text-2xl leading-none">✕</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <!-- Serving Info -->
          <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
            <h3 class="font-semibold text-blue-900 mb-2">Informasi Penyajian</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Ukuran Porsi</p>
                <p class="text-lg font-bold text-blue-600" id="modalServingSize">--</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Deskripsi</p>
                <p class="text-lg font-bold text-blue-600" id="modalServingDesc">--</p>
              </div>
            </div>
          </div>

          <!-- Nutrition Facts -->
          <h3 class="font-semibold mb-4 text-lg">Informasi Gizi (per porsi)</h3>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">KALORI</div>
              <div class="text-2xl font-bold text-orange-600" id="modalCalories">--</div>
              <div class="text-xs text-gray-500">kcal</div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">PROTEIN</div>
              <div class="text-2xl font-bold text-red-600" id="modalProtein">--</div>
              <div class="text-xs text-gray-500">g</div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">LEMAK</div>
              <div class="text-2xl font-bold text-yellow-600" id="modalFat">--</div>
              <div class="text-xs text-gray-500">g</div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">KARBOHIDRAT</div>
              <div class="text-2xl font-bold text-purple-600" id="modalCarbs">--</div>
              <div class="text-xs text-gray-500">g</div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">SERAT</div>
              <div class="text-2xl font-bold text-green-600" id="modalFiber">--</div>
              <div class="text-xs text-gray-500">g</div>
            </div>

            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
              <div class="text-xs text-gray-600 font-semibold mb-1">SKOR AHP</div>
              <div class="text-2xl font-bold text-indigo-600" id="modalAhpScore">--</div>
              <div class="text-xs text-gray-500">/10</div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3 pt-4 border-t">
            <button onclick="openAddJournalForm()" id="addToJournalBtn" class="flex-1 bg-green-500 text-white px-4 py-3 rounded font-semibold hover:bg-green-600 flex items-center justify-center gap-2">
              ✅ Tambah ke Journal
            </button>
            <button onclick="closeFoodModal()" class="flex-1 border border-gray-300 text-gray-700 px-4 py-3 rounded font-semibold hover:bg-gray-50">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Journal dari Rekomendasi -->
  <div id="addJournalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
      <!-- Modal Header -->
      <div class="bg-gradient-to-r from-green-400 to-green-500 p-6 text-white flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold">Tambah ke Journal</h2>
          <p class="text-green-100 text-sm mt-1" id="journalFoodName">--</p>
        </div>
        <button onclick="closeAddJournalForm()" class="text-white text-2xl leading-none">✕</button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <form id="addJournalForm" class="space-y-4">
          <!-- Meal Type -->
          <div>
            <label class="block text-sm font-semibold mb-2">Waktu Makan*</label>
            <select name="meal_type" id="journalMealType" required class="w-full border rounded px-3 py-2">
              <option value="">Pilih waktu makan...</option>
              <option value="breakfast">🌅 Sarapan (Breakfast) - 1x per hari</option>
              <option value="lunch">☀️ Makan Siang (Lunch) - 1x per hari</option>
              <option value="dinner">🌙 Makan Malam (Dinner) - 1x per hari</option>
              <option value="snack">🍿 Snack - Unlimited</option>
            </select>
          </div>

          <!-- Date -->
          <div>
            <label class="block text-sm font-semibold mb-2">Tanggal*</label>
            <input name="eaten_at" id="journalEatenAt" type="date" required class="w-full border rounded px-3 py-2" />
          </div>

          <!-- Quantity (Gram only) -->
          <div>
            <label class="block text-sm font-semibold mb-2">Jumlah (gram)*</label>
            <input 
              name="quantity" 
              id="journalQuantity"
              type="number" 
              step="0.1" 
              value="100"
              required 
              class="w-full border rounded px-3 py-2" 
              placeholder="100"
            />
          </div>
          <input type="hidden" name="unit" value="gram" />

          <!-- Buttons -->
          <div class="flex gap-2 pt-4">
            <button type="submit" id="addJournalSubmitBtn" class="flex-1 bg-green-500 text-white px-4 py-2 rounded font-semibold hover:bg-green-600 disabled:bg-gray-400 disabled:cursor-not-allowed">
              ✅ Simpan
            </button>
            <button type="button" onclick="closeAddJournalForm()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded font-semibold hover:bg-gray-500">
              ❌ Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  </div>
</div>

<script>
  const loginPrompt = document.getElementById('loginPrompt');
  const recommendationContent = document.getElementById('recommendationContent');
  const errorMsg = document.getElementById('errorMsg');
  const successMsg = document.getElementById('successMsg');
  const recommendationsGrid = document.getElementById('recommendationsGrid');
  const emptyState = document.getElementById('emptyState');
  const loadingState = document.getElementById('loadingState');
  const refreshBtn = document.getElementById('refreshBtn');
  const foodModal = document.getElementById('foodModal');

  let currentFood = null;

  function showError(msg) {
    errorMsg.textContent = msg;
    errorMsg.classList.remove('hidden');
    setTimeout(() => errorMsg.classList.add('hidden'), 5000);
  }

  function showSuccess(msg) {
    successMsg.textContent = msg;
    successMsg.classList.remove('hidden');
    setTimeout(() => successMsg.classList.add('hidden'), 5000);
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
      console.log(`Recommendation: Next refresh in ${(msUntilMidnight / 1000 / 60).toFixed(1)} minutes`);
      
      setTimeout(() => {
        console.log('Recommendation: Midnight reached, refreshing data...');
        loadRecommendations();
        scheduleRefresh(); // Schedule next refresh
      }, msUntilMidnight);
    }

    scheduleRefresh();
  }

  function checkAuthentication() {
    return new Promise((resolve) => {
      function check() {
        if (window.axios) {
          const token = localStorage.getItem('auth_token');
          if (token) {
            recommendationContent.classList.remove('hidden');
            loginPrompt.classList.add('hidden');
            loadRecommendations();
            setupMidnightRefresh();
            resolve(true);
          } else {
            loginPrompt.classList.remove('hidden');
            recommendationContent.classList.add('hidden');
            resolve(false);
          }
        } else {
          setTimeout(check, 100);
        }
      }
      check();
    });
  }

  async function loadRecommendations() {
    try {
      loadingState.classList.remove('hidden');
      recommendationsGrid.classList.add('hidden');
      emptyState.classList.add('hidden');

      // Get user profile first for diet goal and target calories
      const profileRes = await window.axios.get('/profile');
      const profile = profileRes.data;

      // Format diet goal display
      const dietGoalMap = {
        'deficit': '📉 Diet (Turun Berat)',
        'maintain': '➡️ Maintain (Stabil)',
        'bulking': '📈 Bulking (Naik Berat)'
      };

      document.getElementById('dietGoalDisplay').textContent = dietGoalMap[profile.diet_goal] || profile.diet_goal;
      document.getElementById('targetCaloriesDisplay').textContent = (profile.target_calories || 0).toLocaleString();

      // Format date
      const today = new Date();
      const dateStr = today.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
      document.getElementById('dateDisplay').textContent = dateStr;

      // Get today's journal to calculate consumed calories
      const journalRes = await window.axios.get('/journal');
      const journalEntries = journalRes.data || [];

      let consumedCalories = 0;
      journalEntries.forEach(entry => {
        if (entry.food) {
          // All quantities are in grams
          const cal = (entry.food.calories * entry.quantity / 100);
          consumedCalories += cal;
        }
      });

      // Calculate remaining calories and progress
      const targetCalories = profile.target_calories || 0;
      const remainingCalories = Math.max(targetCalories - consumedCalories, 0);
      const consumedPercent = targetCalories > 0 ? Math.min(100, (consumedCalories / targetCalories) * 100) : 0;
      const remainingPercent = Math.max(0, 100 - consumedPercent);
      const isExceeded = consumedCalories > targetCalories;
      
      // Update consumed calories display
      document.getElementById('consumedCaloriesDisplay').textContent = Math.round(consumedCalories);
      document.getElementById('remainingCalories').textContent = Math.round(remainingCalories) + ' kcal';
      document.getElementById('calorieProgressPercent').textContent = consumedPercent.toFixed(1);
      document.getElementById('remainingPercent').textContent = remainingPercent.toFixed(1);
      
      // Update progress bars
      document.getElementById('calorieProgressBar').style.width = consumedPercent + '%';
      document.getElementById('remainingProgressBar').style.width = remainingPercent + '%';
      
      // Show/hide exceeded warning and disable form
      const exceededWarning = document.getElementById('exceededWarning');
      const addToJournalBtn = document.getElementById('addToJournalBtn');
      const addJournalSubmitBtn = document.getElementById('addJournalSubmitBtn');
      const journalFormInputs = document.querySelectorAll('#addJournalForm input, #addJournalForm select');
      
      if (isExceeded) {
        exceededWarning.classList.remove('hidden');
        addToJournalBtn.disabled = true;
        addToJournalBtn.classList.add('opacity-50', 'cursor-not-allowed');
        addJournalSubmitBtn.disabled = true;
        journalFormInputs.forEach(input => input.disabled = true);
      } else {
        exceededWarning.classList.add('hidden');
        addToJournalBtn.disabled = false;
        addToJournalBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        addJournalSubmitBtn.disabled = false;
        journalFormInputs.forEach(input => input.disabled = false);
      }

      // Get recommendations
      const res = await window.axios.get('/recommendations/today');
      const recommendations = res.data.recommendations || [];

      loadingState.classList.add('hidden');

      if (recommendations.length === 0) {
        emptyState.classList.remove('hidden');
        return;
      }

      // Clear and populate grid
      recommendationsGrid.innerHTML = '';
      recommendations.forEach(food => {
        const card = createFoodCard(food);
        recommendationsGrid.appendChild(card);
      });

      recommendationsGrid.classList.remove('hidden');
    } catch (err) {
      console.error('Load recommendations error:', err);
      loadingState.classList.add('hidden');
      
      if (err.response?.status === 401) {
        loginPrompt.classList.remove('hidden');
        recommendationContent.classList.add('hidden');
        localStorage.removeItem('auth_token');
      } else {
        showError(err.response?.data?.message || 'Gagal memuat rekomendasi');
      }
    }
  }

  function createFoodCard(food) {
    const card = document.createElement('div');
    card.className = 'bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer border border-gray-200';
    card.onclick = () => openFoodModal(food);

    // Calculate score percentage (0-10)
    const scorePercentage = Math.min((food.ahp_score || 0) * 10, 10);
    const scoreColor = scorePercentage >= 8 ? 'text-green-600' : scorePercentage >= 6 ? 'text-yellow-600' : 'text-orange-600';

    card.innerHTML = `
      <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-800 mb-1">${food.name}</h3>
        <p class="text-sm text-gray-500">${food.source || 'Local'}</p>
      </div>

      <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-orange-50 p-3 rounded border border-orange-100">
          <div class="text-xs text-gray-600 font-semibold">Kalori</div>
          <div class="text-xl font-bold text-orange-600">${food.calories}</div>
          <div class="text-xs text-gray-500">kcal</div>
        </div>

        <div class="bg-red-50 p-3 rounded border border-red-100">
          <div class="text-xs text-gray-600 font-semibold">Protein</div>
          <div class="text-xl font-bold text-red-600">${food.protein?.toFixed(1)}</div>
          <div class="text-xs text-gray-500">g</div>
        </div>

        <div class="bg-yellow-50 p-3 rounded border border-yellow-100">
          <div class="text-xs text-gray-600 font-semibold">Lemak</div>
          <div class="text-xl font-bold text-yellow-600">${food.fat?.toFixed(1)}</div>
          <div class="text-xs text-gray-500">g</div>
        </div>

        <div class="bg-purple-50 p-3 rounded border border-purple-100">
          <div class="text-xs text-gray-600 font-semibold">Karbo</div>
          <div class="text-xl font-bold text-purple-600">${food.carbohydrates?.toFixed(1)}</div>
          <div class="text-xs text-gray-500">g</div>
        </div>
      </div>

      <div class="flex items-center justify-between pt-3 border-t">
        <span class="text-sm text-gray-600">Skor Rekomendasi</span>
        <div class="text-xl font-bold ${scoreColor}">${scorePercentage.toFixed(1)}/10 ⭐</div>
      </div>
    `;

    return card;
  }

  function openFoodModal(food) {
    currentFood = food;

    document.getElementById('modalFoodName').textContent = food.name;
    document.getElementById('modalFoodSource').textContent = food.source || 'Local Food Database';
    document.getElementById('modalServingSize').textContent = food.serving_size ? `${food.serving_size}g` : '--';
    document.getElementById('modalServingDesc').textContent = food.serving_description || '--';

    document.getElementById('modalCalories').textContent = food.calories;
    document.getElementById('modalProtein').textContent = food.protein?.toFixed(1) || '--';
    document.getElementById('modalFat').textContent = food.fat?.toFixed(1) || '--';
    document.getElementById('modalCarbs').textContent = food.carbohydrates?.toFixed(1) || '--';
    document.getElementById('modalFiber').textContent = food.fiber?.toFixed(1) || '--';

    const scorePercentage = Math.min((food.ahp_score || 0) * 10, 10);
    document.getElementById('modalAhpScore').textContent = scorePercentage.toFixed(1);

    foodModal.classList.remove('hidden');
  }

  function closeFoodModal() {
    foodModal.classList.add('hidden');
    currentFood = null;
  }

  async function addToJournal() {
    if (!currentFood) return;

    try {
      const today = new Date().toISOString().split('T')[0];
      
      await window.axios.post('/journal', {
        food_id: currentFood.id,
        date: today,
        quantity: 1
      });

      showSuccess('Makanan berhasil ditambahkan ke journal!');
      closeFoodModal();
    } catch (err) {
      console.error('Add to journal error:', err);
      showError(err.response?.data?.message || 'Gagal menambahkan ke journal');
    }
  }

  // Open form untuk tambah ke journal
  function openAddJournalForm() {
    if (!currentFood) return;

    // Set food name di modal
    document.getElementById('journalFoodName').textContent = currentFood.name;

    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('journalEatenAt').value = today;

    // Set quantity to 100g
    document.getElementById('journalQuantity').value = '100';

    // Show modal
    document.getElementById('addJournalModal').classList.remove('hidden');
  }

  function closeAddJournalForm() {
    document.getElementById('addJournalModal').classList.add('hidden');
    document.getElementById('addJournalForm').reset();
  }

  // Update quantity display and trigger change event
  function updateJournalQuantityDisplay() {
    const event = new Event('input', { bubbles: true });
    document.getElementById('journalQuantity').dispatchEvent(event);
  }

  // Handle form submission
  document.getElementById('addJournalForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!currentFood) {
      showError('Pilih makanan terlebih dahulu');
      return;
    }

    const mealType = document.getElementById('journalMealType').value;
    const eatenAt = document.getElementById('journalEatenAt').value;
    const quantity = parseFloat(document.getElementById('journalQuantity').value);

    if (!mealType) {
      showError('Pilih waktu makan terlebih dahulu');
      return;
    }

    try {
      console.log('Adding to journal:', { 
        food_id: currentFood.id, 
        meal_type: mealType, 
        quantity: quantity, 
        eaten_at: eatenAt 
      });

      const res = await window.axios.post('/journal', {
        food_id: currentFood.id,
        meal_type: mealType,
        quantity: quantity,
        unit: 'gram',
        eaten_at: eatenAt
      });

      console.log('Added to journal:', res.data);
      showSuccess(`${currentFood.name} ditambahkan ke journal!`);
      closeAddJournalForm();
      closeFoodModal();
    } catch (err) {
      console.error('Add to journal error:', err);
      const msg = err.response?.data?.message || err.message || 'Gagal menambahkan ke journal';
      showError(msg);
    }
  });

  // Close modal on outside click
  document.getElementById('addJournalModal').addEventListener('click', (e) => {
    if (e.target.id === 'addJournalModal') {
      closeAddJournalForm();
    }
  });

  // Refresh button handler
  if (refreshBtn) {
    refreshBtn.addEventListener('click', loadRecommendations);
  }

  // Close modal on outside click
  if (foodModal) {
    foodModal.addEventListener('click', (e) => {
      if (e.target === foodModal) {
        closeFoodModal();
      }
    });
  }

  // Initialize - check authentication first
  function waitForAxios() {
    if (window.axios) {
      checkAuthentication();
    } else {
      setTimeout(waitForAxios, 100);
    }
  }

  waitForAxios();
</script>

@endsection
