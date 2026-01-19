@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="mb-6">
  <h1 class="text-3xl font-bold text-gray-900 mb-2">📚 Artikel Kesehatan</h1>
  <p class="text-gray-600">Kumpulan artikel terpercaya tentang nutrisi, diet, dan tips gaya hidup sehat</p>
</div>

<!-- Main Container -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  
  <!-- Left Side - Articles List (2/3) -->
  <div class="lg:col-span-2">
    <div id="articlesList" class="space-y-4">
      <div class="text-center py-12">
        <div class="inline-block">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"></path>
          </svg>
          <p class="text-gray-500 font-medium">Loading articles...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side - Info (1/3) -->
  <div class="lg:col-span-1">
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg shadow-md border border-amber-100 p-5 sticky top-24">
      <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        📚 Tentang Artikel
      </h3>
      <p class="text-sm text-gray-700 mb-4">
        Pelajari tips dan trik kesehatan dari expert Eatera. Setiap artikel dirancang untuk membantu perjalanan diet dan gaya hidup sehat Anda.
      </p>
      <div class="space-y-2 mb-4">
        <p class="text-sm"><strong>📖 Total Artikel:</strong> <span id="totalArticles" class="text-amber-600">-</span></p>
        <p class="text-sm"><strong>📝 Kategori:</strong> Nutrisi, Diet, Tips Kesehatan</p>
        <p class="text-sm"><strong>✍️ Penulis:</strong> Tim Expert Eatera</p>
      </div>
      <div class="p-3 bg-amber-100 rounded text-xs text-amber-800 border border-amber-200">
        ✨ Artikel baru setiap minggu untuk menginspirasi perjalanan diet Anda
      </div>
    </div>

    <!-- Back to Community -->
    <div class="mt-4">
      <a href="/komunitas" class="block text-center bg-rose-500 hover:bg-rose-600 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
        ← Kembali ke Komunitas
      </a>
    </div>
  </div>
</div>

<script>
const API_BASE = '/community';
let currentUser = null;

// Notification function
function showNotification(message, type = 'info', duration = 4000) {
  const container = document.getElementById('notificationContainer') || document.body;
  const notificationId = 'notification-' + Date.now();
  
  const bgColor = {
    'success': 'bg-green-50',
    'error': 'bg-red-50',
    'warning': 'bg-yellow-50',
    'info': 'bg-blue-50'
  }[type] || 'bg-blue-50';
  
  const borderColor = {
    'success': 'border-green-200',
    'error': 'border-red-200',
    'warning': 'border-yellow-200',
    'info': 'border-blue-200'
  }[type] || 'border-blue-200';
  
  const textColor = {
    'success': 'text-green-700',
    'error': 'text-red-700',
    'warning': 'text-yellow-700',
    'info': 'text-blue-700'
  }[type] || 'text-blue-700';

  const html = `
    <div id="${notificationId}" class="fixed top-4 right-4 ${bgColor} border ${borderColor} rounded-lg px-4 py-3 shadow-lg max-w-sm z-50 ${textColor} animate-in fade-in slide-in-from-right">
      ${message}
    </div>
  `;

  container.insertAdjacentHTML('beforeend', html);

  setTimeout(() => {
    const el = document.getElementById(notificationId);
    if (el) el.remove();
  }, duration);
}

// Fetch articles
async function fetchArticles() {
  try {
    const res = await window.axios.get(`${API_BASE}/articles`);
    const articles = res.data;
    
    document.getElementById('totalArticles').textContent = articles.length;
    
    if (articles.length === 0) {
      document.getElementById('articlesList').innerHTML = `
        <div class="text-center py-12">
          <div class="inline-block">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.76 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9-3.507 9-7.747m0 13c5.5 0 9-3.507 9-7.747"></path>
            </svg>
            <p class="text-gray-500 font-medium">Belum ada artikel</p>
          </div>
        </div>
      `;
      return;
    }
    
    const html = articles.map(article => `
      <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition p-5">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">${article.title}</h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">${article.content.substring(0, 150)}...</p>
        <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
          <span>📝 ${article.admin?.name || 'Admin'}</span>
          <span>📅 ${new Date(article.created_at).toLocaleDateString('id-ID')}</span>
        </div>
        <button 
          class="w-full text-rose-500 hover:text-rose-600 hover:bg-rose-50 font-semibold text-sm transition p-2 rounded border border-rose-200"
          onclick="viewArticle(${article.id})"
        >
          Baca Selengkapnya →
        </button>
      </div>
    `).join('');
    
    document.getElementById('articlesList').innerHTML = html;
  } catch (err) {
    console.error('Error fetching articles:', err);
    document.getElementById('articlesList').innerHTML = `
      <div class="text-center py-12">
        <p class="text-red-500 font-medium">❌ Gagal load artikel</p>
      </div>
    `;
  }
}

// View article detail
async function viewArticle(articleId) {
  try {
    const res = await window.axios.get(`${API_BASE}/articles/${articleId}`);
    const article = res.data;
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto';
    modal.innerHTML = `
      <div class="bg-white rounded-2xl max-w-4xl w-full my-8 shadow-2xl overflow-hidden">
        <!-- Hero Section with Cover Image -->
        <div class="relative h-96 bg-gradient-to-br from-rose-100 to-amber-100 overflow-hidden">
          ${article.cover_image ? `
            <img src="${article.cover_image}" alt="${article.title}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-30"></div>
          ` : `
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-center text-gray-400">
                <svg class="w-24 h-24 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p>Artikel</p>
              </div>
            </div>
          `}
          <!-- Close Button -->
          <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 bg-white rounded-full p-2 hover:bg-gray-100 transition shadow-lg z-10">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Content Section -->
        <div class="relative px-8 py-12">
          <!-- Title & Meta -->
          <div class="mb-8 pb-8 border-b border-gray-200">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">${article.title}</h1>
              </div>
            </div>
            
            <!-- Author & Date Info -->
            <div class="flex items-center gap-4 flex-wrap">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-400 to-rose-500 flex items-center justify-center text-white font-bold">
                  ${article.admin?.name?.charAt(0).toUpperCase() || 'A'}
                </div>
                <div>
                  <p class="font-semibold text-gray-900">✍️ ${article.admin?.name || 'Tim Expert'}</p>
                  <p class="text-sm text-gray-500">📅 ${new Date(article.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>
              </div>
              <div class="flex gap-3 ml-auto">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">📚 Artikel</span>
                <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 rounded-full text-xs font-semibold">⏱️ ${Math.ceil(article.content.split(' ').length / 200)} min baca</span>
              </div>
            </div>
          </div>

          <!-- Article Content -->
          <div class="prose prose-lg max-w-none mb-12">
            <article class="text-gray-700 leading-relaxed">
              ${article.content.replace(/\n\n/g, '</p><p class="mb-6 text-justify">').replace(/\n/g, '<br>').split('<p').map((p, i) => i === 0 ? `<p class="mb-6 text-justify">${p}` : `<p class="mb-6 text-justify">${p}`).join('')}
            </article>
          </div>

          <!-- Divider -->
          <div class="my-8 border-t-2 border-gray-200"></div>

          <!-- Footer Section -->
          <div class="bg-gradient-to-r from-rose-50 to-amber-50 rounded-xl p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">📖 Terima kasih sudah membaca</p>
                <p class="text-lg font-semibold text-gray-900">Artikel ditulis oleh Tim Expert Eatera</p>
              </div>
              <div class="text-4xl">🎯</div>
            </div>
            <p class="text-sm text-gray-600 mt-4">Konsultasikan pertanyaan Anda dengan ahli gizi atau dokter untuk saran yang lebih personal.</p>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3 mt-8 pt-8 border-t border-gray-200">
            <button onclick="this.closest('.fixed').remove()" class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition">
              ✕ Tutup
            </button>
            <button onclick="navigator.share({title: '${article.title}', text: 'Baca artikel menarik ini', url: window.location.href}).catch(e => alert('Share tidak tersedia'))" class="flex-1 px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-lg transition">
              📤 Bagikan
            </button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  } catch (err) {
    console.error('Error fetching article:', err);
    showNotification('Gagal load artikel', 'error');
  }
}

// Initialize
fetchArticles();

// Auto-refresh setiap 30 detik
setInterval(() => {
  fetchArticles();
}, 30000);
</script>

@endsection
