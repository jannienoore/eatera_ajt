@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
  <h1 class="text-4xl font-bold mb-8">Food Journal</h1>
  
  <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-4"></div>
  <div id="successMsg" class="hidden bg-green-100 text-green-700 p-4 rounded mb-4"></div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Input Form & Food List -->
    <div class="lg:col-span-2">
      <!-- Warning: Exceeded Calorie Target -->
      <div id="exceededWarning" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <div class="flex items-start gap-3">
          <div class="text-2xl">⚠️</div>
          <div>
            <h3 class="font-bold text-red-700 mb-1">Target Kalori Terlampaui!</h3>
            <p class="text-red-600 text-sm">Anda sudah melebihi target kalori harian. Input makanan tidak dapat dilakukan.</p>
          </div>
        </div>
      </div>

      <!-- Add Food Section -->
      <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold mb-6">Tambah Makanan</h2>
        
        <form id="journalForm" class="space-y-4">
          <!-- Meal Type -->
          <div>
            <label class="block text-sm font-semibold mb-2">Waktu Makan*</label>
            <select name="meal_type" required class="w-full border rounded px-3 py-2">
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
            <input name="eaten_at" type="date" required class="w-full border rounded px-3 py-2" />
          </div>

          <!-- Food Search -->
          <div>
            <label class="block text-sm font-semibold mb-2">Pilih Makanan*</label>
            <div class="relative">
              <input 
                id="foodSearch" 
                type="text" 
                placeholder="Cari makanan (misal: nasi, ayam, smoothie)..." 
                class="w-full border rounded px-3 py-2"
              />
              <div id="foodSearchResults" class="hidden absolute top-full left-0 right-0 bg-white border border-t-0 rounded-b max-h-48 overflow-y-auto z-10"></div>
            </div>
            <input name="food_id" type="hidden" id="selectedFoodId" />
            <div id="selectedFoodDisplay" class="mt-2 p-3 bg-blue-50 rounded hidden">
              <div class="text-sm font-semibold" id="selectedFoodName"></div>
              <div class="text-xs text-gray-600 mt-1" id="selectedFoodNutrition"></div>
              <div class="text-xs text-gray-500 mt-2" id="selectedFoodServing"></div>
              <div class="text-sm font-bold text-blue-600 mt-2" id="selectedFoodCalories"></div>
            </div>
          </div>

          <!-- Quantity (Gram only) -->
          <div>
            <label class="block text-sm font-semibold mb-2">Jumlah (gram)*</label>
            <input 
              name="quantity" 
              id="quantityInput"
              type="number" 
              step="0.1" 
              value="100"
              required 
              class="w-full border rounded px-3 py-2" 
              placeholder="100"
            />
          </div>
          <input type="hidden" name="unit" value="gram" />

          <!-- Button -->
          <button type="submit" id="submitBtn" class="w-full bg-rose-400 text-white px-6 py-3 rounded font-semibold hover:bg-rose-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
            ➕ Tambah ke Journal
          </button>
        </form>
      </div>

      <!-- Edit Modal -->
      <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <h3 class="text-2xl font-bold mb-4">Edit Makanan</h3>
          
          <form id="editForm" class="space-y-4">
            <!-- Meal Type -->
            <div>
              <label class="block text-sm font-semibold mb-2">Waktu Makan*</label>
              <select name="meal_type" id="editMealType" required class="w-full border rounded px-3 py-2">
                <option value="">Pilih waktu makan...</option>
                <option value="breakfast">🌅 Sarapan (Breakfast)</option>
                <option value="lunch">☀️ Makan Siang (Lunch)</option>
                <option value="dinner">🌙 Makan Malam (Dinner)</option>
                <option value="snack">🍿 Snack</option>
              </select>
            </div>

            <!-- Date -->
            <div>
              <label class="block text-sm font-semibold mb-2">Tanggal*</label>
              <input name="eaten_at" id="editEatenAt" type="date" required class="w-full border rounded px-3 py-2" />
            </div>

            <!-- Food Name (Read-only) -->
            <div>
              <label class="block text-sm font-semibold mb-2">Makanan</label>
              <input id="editFoodName" type="text" readonly class="w-full border rounded px-3 py-2 bg-gray-100" />
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-semibold mb-2">Jumlah (gram)*</label>
              <input 
                name="quantity" 
                id="editQuantity"
                type="number" 
                step="0.1" 
                required 
                class="w-full border rounded px-3 py-2" 
                placeholder="100"
              />
            </div>
            <input type="hidden" name="unit" value="gram" />

            <!-- Buttons -->
            <div class="flex gap-2">
              <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded font-semibold hover:bg-blue-600">
                💾 Simpan
              </button>
              <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded font-semibold hover:bg-gray-500">
                ❌ Batal
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Available Foods List -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">📋 Daftar Makanan Tersedia</h2>
        
        <!-- Search Filter -->
        <div class="mb-4">
          <input 
            id="foodsListSearch" 
            type="text" 
            placeholder="🔍 Cari makanan (nama, kalori, nutrisi)..." 
            class="w-full border rounded px-3 py-2"
          />
        </div>
        
        <div id="foodsList" class="grid grid-cols-1 gap-2">
          <div class="text-center py-8 text-gray-500">Loading foods...</div>
        </div>
      </div>
    </div>

    <!-- Right: Today's Journal -->
    <div class="lg:col-span-1">
      <div class="bg-white p-6 rounded-lg shadow sticky top-4 flex flex-col max-h-[calc(100vh-120px)]">
        <h2 class="text-2xl font-bold mb-4">📅 Hari Ini</h2>
        
        <!-- Calories Summary -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded mb-4">
          <div class="text-sm text-gray-600">Total Kalori</div>
          <div class="text-3xl font-bold text-blue-600" id="todayTotalCal">0</div>
          <div class="text-xs text-gray-500 mt-1">kcal</div>
        </div>

        <!-- Journal Entries by Meal Type -->
        <div class="space-y-4 overflow-y-auto flex-1 pr-2">
          <!-- Breakfast -->
          <div class="border-l-4 border-orange-400 bg-orange-50 p-3 rounded">
            <div class="font-semibold text-sm text-gray-700 mb-2">🌅 Sarapan</div>
            <div id="breakfastEntries" class="space-y-1 text-sm">
              <div class="text-gray-500 text-xs">Belum ada</div>
            </div>
          </div>

          <!-- Lunch -->
          <div class="border-l-4 border-yellow-400 bg-yellow-50 p-3 rounded">
            <div class="font-semibold text-sm text-gray-700 mb-2">☀️ Makan Siang</div>
            <div id="lunchEntries" class="space-y-1 text-sm">
              <div class="text-gray-500 text-xs">Belum ada</div>
            </div>
          </div>

          <!-- Dinner -->
          <div class="border-l-4 border-purple-400 bg-purple-50 p-3 rounded">
            <div class="font-semibold text-sm text-gray-700 mb-2">🌙 Makan Malam</div>
            <div id="dinnerEntries" class="space-y-1 text-sm">
              <div class="text-gray-500 text-xs">Belum ada</div>
            </div>
          </div>

          <!-- Snack -->
          <div class="border-l-4 border-pink-400 bg-pink-50 p-3 rounded">
            <div class="font-semibold text-sm text-gray-700 mb-2">🍿 Snack</div>
            <div id="snackEntries" class="space-y-1 text-sm">
              <div class="text-gray-500 text-xs">Belum ada</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const errorMsg = document.getElementById('errorMsg');
const successMsg = document.getElementById('successMsg');
const form = document.getElementById('journalForm');
const foodSearch = document.getElementById('foodSearch');
const foodSearchResults = document.getElementById('foodSearchResults');
const selectedFoodId = document.getElementById('selectedFoodId');
const selectedFoodDisplay = document.getElementById('selectedFoodDisplay');
const foodsList = document.getElementById('foodsList');
const foodsListSearch = document.getElementById('foodsListSearch');

let allFoods = [];
let selectedFood = null;

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

// Calculate calories based on quantity (gram only)
function calculateCalories(food, quantity) {
  return (food.calories * quantity / 100).toFixed(1);
}

// Update display when quantity changes
function updateQuantityDisplay() {
  if (!selectedFood) return;
  
  const quantity = parseFloat(document.getElementById('quantityInput').value);
  
  const cal = calculateCalories(selectedFood, quantity);
  
  document.getElementById('selectedFoodCalories').textContent = 
    `${cal} kcal (${quantity.toFixed(1)}g)`;
}

// Render foods list with optional filter
function renderFoodsList(query = '') {
  let filtered = allFoods;
  
  if (query.trim()) {
    const q = query.toLowerCase();
    filtered = allFoods.filter(food => 
      food.name.toLowerCase().includes(q) ||
      food.calories.toString().includes(q) ||
      (food.protein && food.protein.toString().includes(q)) ||
      (food.fat && food.fat.toString().includes(q)) ||
      (food.carbohydrates && food.carbohydrates.toString().includes(q))
    );
  }
  
  if (filtered.length === 0) {
    foodsList.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada makanan yang sesuai</div>';
    return;
  }
  
  foodsList.innerHTML = filtered.map(food => `
    <div class="p-3 bg-gray-50 rounded border hover:bg-gray-100 cursor-pointer transition"
         onclick="selectFoodFromList(${food.id}, '${food.name.replace(/'/g, "\\'")}', ${food.calories}, ${food.protein || 0}, ${food.fat || 0}, ${food.carbohydrates || 0}, ${food.fiber || 0})">
      <div class="font-semibold text-sm">${food.name}</div>
      <div class="text-xs text-gray-600 mt-1">
        💪 ${food.protein || 0}g protein | 🔥 ${food.fat || 0}g fat | 🌾 ${food.carbohydrates || 0}g carbs | ${food.calories} kcal
      </div>
      ${food.serving_description ? `<div class="text-xs text-gray-500 mt-1">Serving: ${food.serving_description}</div>` : ''}
    </div>
  `).join('');
}

// Search foodsList on input
foodsListSearch.addEventListener('input', (e) => {
  renderFoodsList(e.target.value);
});

// Listen for quantity changes
document.getElementById('quantityInput').addEventListener('input', updateQuantityDisplay);

// Load all foods
async function loadFoods() {
  try {
    console.log('Loading foods...');
    const res = await window.axios.get('/foods');
    allFoods = res.data;
    console.log('Foods loaded:', allFoods.length);
    
    // Display foods list
    renderFoodsList();
  } catch (err) {
    console.error('Load foods error:', err);
    showError('Gagal memuat daftar makanan');
  }
}

// Food search functionality
foodSearch.addEventListener('input', (e) => {
  const query = e.target.value.toLowerCase().trim();
  
  if (!query) {
    foodSearchResults.classList.add('hidden');
    return;
  }
  
  const results = allFoods.filter(food => 
    food.name.toLowerCase().includes(query)
  );
  
  if (results.length === 0) {
    foodSearchResults.innerHTML = '<div class="p-3 text-gray-500 text-sm">Tidak ada hasil</div>';
    foodSearchResults.classList.remove('hidden');
    return;
  }
  
  foodSearchResults.innerHTML = results.map(food => `
    <div class="p-3 border-b hover:bg-gray-100 cursor-pointer transition text-sm"
         onclick="selectFood(${food.id}, '${food.name.replace(/'/g, "\\'")}', ${food.calories}, ${food.protein}, ${food.fat}, ${food.carbohydrates}, ${food.fiber})">
      <div class="font-semibold">${food.name}</div>
      <div class="text-xs text-gray-600">${food.calories} kcal | P: ${food.protein || 0}g | F: ${food.fat || 0}g | C: ${food.carbohydrates || 0}g</div>
    </div>
  `).join('');
  
  foodSearchResults.classList.remove('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('#foodSearch') && !e.target.closest('#foodSearchResults')) {
    foodSearchResults.classList.add('hidden');
  }
});

function selectFood(id, name, calories, protein, fat, carbs, fiber) {
  const food = allFoods.find(f => f.id === id);
  selectFoodCommon(id, name, calories, protein, fat, carbs, fiber, food);
  foodSearchResults.classList.add('hidden');
}

function selectFoodFromList(id, name, calories, protein, fat, carbs, fiber) {
  const food = allFoods.find(f => f.id === id);
  selectFoodCommon(id, name, calories, protein, fat, carbs, fiber, food);
}

function selectFoodCommon(id, name, calories, protein, fat, carbs, fiber, fullFood) {
  selectedFoodId.value = id;
  selectedFood = { id, name, calories, protein, fat, carbs, fiber };
  
  foodSearch.value = name;
  selectedFoodDisplay.classList.remove('hidden');
  document.getElementById('selectedFoodName').textContent = name;
  document.getElementById('selectedFoodNutrition').textContent = 
    `${calories} kcal | 💪 ${protein}g protein | 🔥 ${fat}g fat | 🌾 ${carbs}g carbs | 🌱 ${fiber}g fiber`;
  
  // Reset quantity to 100g default
  document.getElementById('quantityInput').value = '100';
  
  updateQuantityDisplay();
  
  foodSearchResults.classList.add('hidden');
}

// Form submission
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  errorMsg.classList.add('hidden');
  successMsg.classList.add('hidden');
  
  if (!selectedFood) {
    showError('Pilih makanan terlebih dahulu');
    return;
  }
  
  const mealType = form.meal_type.value;
  const quantity = parseFloat(form.quantity.value);
  const eatenAt = form.eaten_at.value;
  
  try {
    console.log('Adding food journal:', { food_id: selectedFood.id, meal_type: mealType, quantity: quantity, eaten_at: eatenAt });
    const res = await window.axios.post('/journal', {
      food_id: selectedFood.id,
      meal_type: mealType,
      quantity: quantity,
      unit: 'gram',
      eaten_at: eatenAt
    });
    
    console.log('Food added:', res.data);
    showSuccess(`${selectedFood.name} ditambahkan!`);
    
    // Reset form
    form.reset();
    selectedFoodId.value = '';
    selectedFood = null;
    selectedFoodDisplay.classList.add('hidden');
    foodSearch.value = '';
    
    // Reload journal
    loadJournal(eatenAt);
  } catch (err) {
    console.error('Add food error:', err);
    const msg = err.response?.data?.message || err.message || 'Gagal menambahkan makanan';
    showError(msg);
  }
});

// Load today's journal
async function loadJournal(date) {
  try {
    // Helper function to get today's date string (YYYY-MM-DD)
    function getTodayDateString() {
      const now = new Date();
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const day = String(now.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }
    
    const dateStr = date || getTodayDateString();
    console.log('Loading journal for:', dateStr);
    const res = await window.axios.get('/journal', { params: { date: dateStr } });
    const entries = res.data;
    console.log('Journal entries:', entries);
    
    // Clear sections
    ['breakfastEntries', 'lunchEntries', 'dinnerEntries', 'snackEntries'].forEach(id => {
      document.getElementById(id).innerHTML = '<div class="text-gray-500 text-xs">Belum ada</div>';
    });
    
    // Calculate total
    let totalCal = 0;
    
    // Group by meal type
    entries.forEach(entry => {
      const food = entry.food;
      
      // All quantities are in grams now
      const cal = (food.calories * entry.quantity / 100).toFixed(2);
      
      const html = `
        <div class="flex justify-between items-center p-2 bg-white rounded text-xs">
          <div>
            <div class="font-semibold">${food.name}</div>
            <div class="text-gray-600">${entry.quantity}g = ${cal} kcal</div>
          </div>
          <button type="button" onclick="editEntry(${entry.id}, ${entry.food_id}, '${food.name.replace(/'/g, "\\'")}', ${entry.quantity}, '${entry.meal_type}', '${dateStr}')" class="text-blue-500 hover:text-blue-700 text-lg">✏️</button>
        </div>
      `;
      
      const sectionId = entry.meal_type + 'Entries';
      const section = document.getElementById(sectionId);
      if (section.innerHTML.includes('Belum ada')) {
        section.innerHTML = html;
      } else {
        section.innerHTML += html;
      }
      
      totalCal += parseFloat(cal);
    });
    
    document.getElementById('todayTotalCal').textContent = totalCal.toFixed(0);
    
    // Check if exceeded and disable form
    const userProfile = await window.axios.get('/user');
    const targetCalories = userProfile.data.profile?.target_calories || 2000;
    const isExceeded = totalCal >= targetCalories;
    
    const exceededWarning = document.getElementById('exceededWarning');
    const submitBtn = document.getElementById('submitBtn');
    const formInputs = document.querySelectorAll('#journalForm input, #journalForm select');
    
    if (isExceeded) {
      exceededWarning.classList.remove('hidden');
      submitBtn.disabled = true;
      formInputs.forEach(input => input.disabled = true);
    } else {
      exceededWarning.classList.add('hidden');
      submitBtn.disabled = false;
      formInputs.forEach(input => input.disabled = false);
    }
  } catch (err) {
    console.error('Load journal error:', err);
    if (err.response?.status === 401) {
      window.location.href = '/login';
    }
  }
}

// Edit entry - open modal
function editEntry(id, foodId, foodName, quantity, mealType, dateStr) {
  // Store current entry id and food id for update
  document.getElementById('editForm').dataset.entryId = id;
  document.getElementById('editForm').dataset.dateStr = dateStr;
  document.getElementById('editForm').dataset.foodId = foodId;
  
  // Populate form
  document.getElementById('editMealType').value = mealType;
  document.getElementById('editEatenAt').value = dateStr;
  document.getElementById('editFoodName').value = foodName;
  document.getElementById('editQuantity').value = quantity;
  
  // Show modal
  document.getElementById('editModal').classList.remove('hidden');
}

// Close edit modal
function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
  document.getElementById('editForm').reset();
  delete document.getElementById('editForm').dataset.entryId;
  delete document.getElementById('editForm').dataset.dateStr;
}

// Update entry
document.getElementById('editForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  errorMsg.classList.add('hidden');
  successMsg.classList.add('hidden');
  
  const entryId = document.getElementById('editForm').dataset.entryId;
  const dateStr = document.getElementById('editForm').dataset.dateStr;
  
  const mealType = document.getElementById('editMealType').value;
  const eatenAt = document.getElementById('editEatenAt').value;
  const quantity = parseFloat(document.getElementById('editQuantity').value);
  
  // Get current food_id from the form dataset or from selectedFood
  const foodId = document.getElementById('editForm').dataset.foodId;
  
  try {
    console.log('Updating food journal:', { food_id: foodId, meal_type: mealType, quantity, eaten_at: eatenAt });
    const res = await window.axios.put(`/journal/${entryId}`, {
      food_id: foodId,
      meal_type: mealType,
      quantity: quantity,
      unit: 'gram',
      eaten_at: eatenAt
    });
    
    console.log('Food updated:', res.data);
    showSuccess('Makanan berhasil diubah!');
    closeEditModal();
    
    // Reload journal
    loadJournal(eatenAt);
  } catch (err) {
    console.error('Update food error:', err);
    const msg = err.response?.data?.message || err.message || 'Gagal mengubah makanan';
    showError(msg);
  }
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

  function getTodayDateString() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function scheduleRefresh() {
    const msUntilMidnight = calculateMsUntilMidnight();
    console.log(`Food Journal: Next refresh in ${(msUntilMidnight / 1000 / 60).toFixed(1)} minutes`);
    
    setTimeout(() => {
      console.log('Food Journal: Midnight reached, refreshing data...');
      // Reset date to today
      document.querySelector('input[name="eaten_at"]').value = getTodayDateString();
      loadJournal();
      scheduleRefresh(); // Schedule next refresh
    }, msUntilMidnight);
  }

  scheduleRefresh();
}

// Initialize
async function init() {
  // Helper function to get today's date string (YYYY-MM-DD)
  function getTodayDateString() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  // Set today as default date
  document.querySelector('input[name="eaten_at"]').value = getTodayDateString();
  
  await loadFoods();
  loadJournal();
  setupMidnightRefresh();
}

// Wait for axios then initialize
function waitForAxios() {
  if (window.axios) {
    init();
  } else {
    setTimeout(waitForAxios, 100);
  }
}

waitForAxios();
</script>
@endsection
