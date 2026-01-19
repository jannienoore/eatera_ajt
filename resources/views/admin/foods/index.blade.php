@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
  <h1 class="text-3xl font-bold">Kelola Makanan</h1>
  <div class="space-x-2">
    <button id="importBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">📥 Import dari USDA</button>
    <a href="/admin/foods/create" class="bg-rose-400 text-white px-4 py-2 rounded hover:bg-rose-500 text-sm inline-block">➕ Tambah Makanan</a>
  </div>
</div>

<div id="importSection" class="hidden bg-blue-50 border border-blue-200 rounded p-4 mb-6">
  <h3 class="font-bold mb-2">Import dari USDA Database</h3>
  <div class="flex gap-2">
    <input id="usdaSearch" type="text" placeholder="Cari makanan di USDA (misal: apple, chicken)" class="flex-1 border rounded px-3 py-2">
    <button id="searchBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">🔍 Cari</button>
  </div>
  <div id="usdaResults" class="mt-4"></div>
</div>

<div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-4"></div>
<div id="successMsg" class="hidden bg-green-100 text-green-700 p-4 rounded mb-4"></div>

<div class="bg-white rounded shadow overflow-hidden">
  <table class="w-full">
    <thead class="bg-gray-100 border-b">
      <tr>
        <th class="px-4 py-2 text-left text-sm font-semibold">Nama Makanan</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Kalori</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Protein (g)</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Lemak (g)</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Karbo (g)</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Serat (g)</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Source</th>
        <th class="px-4 py-2 text-left text-sm font-semibold">Aksi</th>
      </tr>
    </thead>
    <tbody id="foodsList">
      <tr><td colspan="8" class="text-center py-6 text-gray-500">Loading...</td></tr>
    </tbody>
  </table>
</div>

<script>
const errorMsg = document.getElementById('errorMsg');
const successMsg = document.getElementById('successMsg');
const importBtn = document.getElementById('importBtn');
const importSection = document.getElementById('importSection');
const searchBtn = document.getElementById('searchBtn');
const usdaSearch = document.getElementById('usdaSearch');
const usdaResults = document.getElementById('usdaResults');
const foodsList = document.getElementById('foodsList');

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

// Load foods list
async function loadFoods() {
  try {
    console.log('Loading foods from /admin/foods...');
    const res = await window.axios.get('/admin/foods');
    console.log('Foods response status:', res.status);
    console.log('Foods response data:', res.data);
    console.log('Foods data type:', typeof res.data);
    console.log('Foods data is array?:', Array.isArray(res.data));
    console.log('Foods data length:', res.data?.length);
    
    if (!res.data || !Array.isArray(res.data) || res.data.length === 0) {
      console.log('No foods found or data is not an array');
      foodsList.innerHTML = '<tr><td colspan="8" class="text-center py-6 text-gray-500">Belum ada makanan. Silakan buat atau import.</td></tr>';
      return;
    }
    
    foodsList.innerHTML = res.data.map(food => `
      <tr class="border-b hover:bg-gray-50">
        <td class="px-4 py-3 text-sm">${food.name}</td>
        <td class="px-4 py-3 text-sm">${food.calories}</td>
        <td class="px-4 py-3 text-sm">${food.protein || '-'}</td>
        <td class="px-4 py-3 text-sm">${food.fat || '-'}</td>
        <td class="px-4 py-3 text-sm">${food.carbohydrates || '-'}</td>
        <td class="px-4 py-3 text-sm">${food.fiber || '-'}</td>
        <td class="px-4 py-3 text-sm"><span class="text-xs bg-gray-100 px-2 py-1 rounded">${food.source || 'manual'}</span></td>
        <td class="px-4 py-3 text-sm space-x-2">
          <a href="/admin/foods/${food.id}/edit" class="text-blue-600 hover:underline">Edit</a>
        </td>
      </tr>
    `).join('');
  } catch (err) {
    console.error('Load foods error:', err);
    if (err.response?.status === 401) {
      window.location.href = '/login';
    } else {
      showError('Gagal memuat data makanan: ' + (err.response?.data?.message || err.message));
    }
  }
}

// Import toggle
importBtn.addEventListener('click', () => {
  importSection.classList.toggle('hidden');
});

// Search USDA
searchBtn.addEventListener('click', async () => {
  const query = usdaSearch.value.trim();
  if (!query) {
    showError('Masukkan nama makanan untuk dicari');
    return;
  }
  
  try {
    usdaResults.innerHTML = '<div class="text-center py-4">Mencari...</div>';
    const res = await window.axios.get('/foods/search', { params: { food: query } });
    
    const foods = res.data?.foods || [];
    console.log('USDA Search Results:', foods);
    
    if (foods.length === 0) {
      usdaResults.innerHTML = '<div class="text-center text-gray-500 py-4">Tidak ada hasil</div>';
      return;
    }
    
    usdaResults.innerHTML = '<div class="grid gap-2 max-h-96 overflow-y-auto">' + 
      foods.map(item => {
        const fdcId = item.fdcId;
        const desc = item.description;
        return `
          <div class="flex justify-between items-center bg-gray-50 p-3 rounded border">
            <div>
              <div class="font-semibold text-sm">${desc}</div>
              <div class="text-xs text-gray-500">FDC ID: ${fdcId}</div>
            </div>
            <button onclick="importFoodFromUSDA(${fdcId})" 
                    class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
              Import
            </button>
          </div>
        `;
      }).join('') + '</div>';
  } catch (err) {
    console.error('Search error:', err);
    showError('Gagal mencari: ' + (err.response?.data?.message || err.message));
  }
});

async function importFoodFromUSDA(fdcId) {
  try {
    console.log('Importing food with FDC ID:', fdcId);
    const res = await window.axios.post('/foods/import', {
      fdcId: fdcId
    });
    console.log('Import response:', res.data);
    showSuccess(`"${res.data.name}" berhasil diimport!`);
    usdaResults.innerHTML = '';
    usdaSearch.value = '';
    loadFoods();
  } catch (err) {
    console.error('Import error:', err);
    showError('Gagal import: ' + (err.response?.data?.message || err.message));
  }
}

// Wait for axios to be available before loading
function waitForAxios() {
  if (window.axios) {
    console.log('Axios is ready, loading foods...');
    loadFoods();
  } else {
    console.log('Waiting for axios...');
    setTimeout(waitForAxios, 100);
  }
}

waitForAxios();
</script>
@endsection
