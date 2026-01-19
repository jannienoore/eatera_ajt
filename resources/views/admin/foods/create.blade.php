@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
  <h1 class="text-3xl font-bold mb-6">Tambah Makanan Baru</h1>

  <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-4"></div>

  <div class="bg-white p-6 rounded shadow">
    <form id="createFoodForm" class="space-y-6">
      <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
          <label class="block text-sm font-semibold mb-1">Nama Makanan*</label>
          <input name="name" required class="w-full border rounded px-3 py-2" placeholder="misal: Nasi Goreng Kampung" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Kalori*</label>
          <input name="calories" type="number" step="0.1" required class="w-full border rounded px-3 py-2" placeholder="450" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Protein (g)*</label>
          <input name="protein" type="number" step="0.1" required class="w-full border rounded px-3 py-2" placeholder="12" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Lemak (g)*</label>
          <input name="fat" type="number" step="0.1" required class="w-full border rounded px-3 py-2" placeholder="15" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Karbohidrat (g)*</label>
          <input name="carbohydrates" type="number" step="0.1" required class="w-full border rounded px-3 py-2" placeholder="65" />
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Serat (g)</label>
          <input name="fiber" type="number" step="0.1" class="w-full border rounded px-3 py-2" placeholder="3" />
        </div>
      </div>

      <div class="flex gap-3 pt-4">
        <button type="submit" class="bg-rose-400 text-white px-6 py-2 rounded font-semibold hover:bg-rose-500">💾 Simpan</button>
        <a href="/admin/foods" class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-50">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
const errorMsg = document.getElementById('errorMsg');
const form = document.getElementById('createFoodForm');

function showError(msg) {
  errorMsg.textContent = msg;
  errorMsg.classList.remove('hidden');
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  errorMsg.classList.add('hidden');
  
  const data = {
    name: form.name.value,
    calories: parseFloat(form.calories.value) || 0,
    protein: parseFloat(form.protein.value) || 0,
    fat: parseFloat(form.fat.value) || 0,
    carbohydrates: parseFloat(form.carbohydrates.value) || 0,
    fiber: parseFloat(form.fiber.value) || 0,
    source: 'manual'
  };
  
  try {
    console.log('Creating food:', data);
    const res = await window.axios.post('/admin/foods', data);
    console.log('Food created:', res.data);
    window.location = '/admin/foods';
  } catch (err) {
    console.error('Create error:', err);
    showError(err.response?.data?.message || err.response?.data?.errors ? JSON.stringify(err.response.data.errors) : 'Gagal membuat makanan');
  }
});
</script>

@endsection
