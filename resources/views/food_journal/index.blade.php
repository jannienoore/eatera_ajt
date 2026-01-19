@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
  <h1 class="text-2xl font-bold mb-4">Food Journal</h1>
  <div id="journalList">Loading...</div>
  <form id="addEntry" class="mt-4 space-y-2">
    <input name="name" placeholder="Food name" class="w-full border rounded px-2 py-1" />
    <button class="bg-rose-400 text-white px-3 py-1 rounded">Add</button>
  </form>
</div>

<script type="module">
async function load() {
  try {
    const res = await window.axios.get('/journal');
    document.getElementById('journalList').innerText = JSON.stringify(res.data);
  } catch (err) {
    document.getElementById('journalList').innerText = 'Unable to fetch journal';
  }
}

document.getElementById('addEntry').addEventListener('submit', async (e) => {
  e.preventDefault();
  const name = e.target.name.value;
  try {
    await window.axios.post('/journal', { name });
    e.target.reset();
    load();
  } catch (err) {
    alert('Failed to add');
  }
});

load();
</script>

@endsection
