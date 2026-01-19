@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
  <h1 class="text-4xl font-bold mb-8">🏘️ Kelola Komunitas</h1>
  
  <!-- Development Notice -->
  <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-6 mb-8">
    <div class="flex items-start gap-4">
      <div class="text-4xl">🔨</div>
      <div>
        <h2 class="text-2xl font-bold text-yellow-800 mb-2">Backend sedang dalam tahap pengembangan</h2>
        <p class="text-yellow-700">Fitur Kelola Komunitas belum tersedia. Backend API masih dalam proses pengembangan.</p>
        <p class="text-yellow-700 text-sm mt-2">Harap menunggu update selanjutnya untuk mengakses fitur ini sepenuhnya.</p>
      </div>
    </div>
  </div>

  <!-- Placeholder Content -->
  <div class="bg-white p-8 rounded-lg shadow text-center py-16">
    <div class="text-6xl mb-4">👥</div>
    <h3 class="text-2xl font-semibold text-gray-700 mb-2">Fitur Komunitas</h3>
    <p class="text-gray-600 mb-6">Kelola komunitas pengguna, diskusi, dan interaksi sosial akan segera tersedia.</p>
    <a href="/admin/dashboard" class="inline-block bg-rose-400 text-white px-6 py-3 rounded font-semibold hover:bg-rose-500">
      ← Kembali ke Dashboard
    </a>
  </div>
</div>
@endsection
