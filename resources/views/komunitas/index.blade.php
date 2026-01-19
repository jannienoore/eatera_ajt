@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="mb-6">
  <h1 class="text-3xl font-bold text-gray-900 mb-2">🌍 Komunitas Eatera</h1>
  <p class="text-gray-600">Berbagi pengalaman diet, motivasi, dan tips sehat bersama komunitas kami</p>
</div>

<!-- Tab Navigation -->
<div class="mb-6 border-b border-gray-200">
  <div class="flex gap-4">
    <button 
      id="tabPosts" 
      type="button"
      class="px-4 py-3 font-semibold text-rose-500 border-b-2 border-rose-500 cursor-pointer transition hover:bg-gray-50"
      onclick="switchTab('posts'); return false;"
    >
      📝 Postingan
    </button>
    <button 
      id="tabArticles" 
      type="button"
      class="px-4 py-3 font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-900 cursor-pointer transition hover:bg-gray-50"
      onclick="switchTab('articles'); return false;"
    >
      📚 Artikel
    </button>
  </div>
</div>

<!-- Main Container -->
<div id="postsContainer" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  
  <!-- Left Side - Main Content (2/3) -->
  <div class="lg:col-span-2">
    
    <!-- Create Post Form -->
    <div class="bg-gradient-to-r from-rose-50 to-pink-50 p-6 rounded-lg shadow-md mb-6 border border-rose-100">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">✍️ Bagikan Pengalaman Anda</h2>
      <form id="createPostForm" class="space-y-3">
        <textarea 
          id="postContent" 
          name="content" 
          placeholder="Ceritakan perjalanan diet Anda, tips yang berhasil, atau motivasi Anda hari ini..." 
          class="w-full border border-rose-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none bg-white"
          rows="4"
        ></textarea>
        <div class="flex justify-between items-center">
          <small class="text-gray-500">Maksimal 1000 karakter</small>
          <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-2 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition">
            📤 Posting
          </button>
        </div>
      </form>
      <div id="createPostMessage" class="mt-3 text-sm font-medium" style="display:none;"></div>
    </div>

    <!-- Posts List -->
    <div id="postsList" class="space-y-4">
      <div class="text-center py-12">
        <div class="inline-block">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"></path>
          </svg>
          <p class="text-gray-500 font-medium">Loading posts...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side - Rules & Info (1/3) -->
  <div class="lg:col-span-1">
    
    <!-- Community Rules -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-md border border-blue-100 p-5 mb-6 sticky top-24">
      <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        📋 Peraturan Komunitas
      </h3>
      <ul class="space-y-3 text-sm">
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">1.</span>
          <span class="text-gray-700"><strong>Sopan & Hormat</strong><br><small class="text-gray-600">Saling menghormati antar member</small></span>
        </li>
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">2.</span>
          <span class="text-gray-700"><strong>Konten Relevan</strong><br><small class="text-gray-600">Topik seputar diet, kesehatan, & nutrisi</small></span>
        </li>
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">3.</span>
          <span class="text-gray-700"><strong>Jangan Spam</strong><br><small class="text-gray-600">Hindari promosi produk atau link mencurigakan</small></span>
        </li>
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">4.</span>
          <span class="text-gray-700"><strong>No Hate Speech</strong><br><small class="text-gray-600">Larangan ujaran kebencian & diskriminasi</small></span>
        </li>
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">5.</span>
          <span class="text-gray-700"><strong>Privasi Terjaga</strong><br><small class="text-gray-600">Jangan bagikan data pribadi orang lain</small></span>
        </li>
        <li class="flex items-start gap-3">
          <span class="text-blue-500 font-bold mt-1">6.</span>
          <span class="text-gray-700"><strong>Bukan Medical Advice</strong><br><small class="text-gray-600">Konsultasi dokter untuk masalah serius</small></span>
        </li>
      </ul>
      <div class="mt-4 p-3 bg-blue-100 rounded text-xs text-blue-800 border border-blue-200">
        ⚠️ Post yang melanggar akan di-review oleh admin
      </div>
    </div>

    <!-- Community Stats -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-5">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Info Komunitas</h3>
      <div class="space-y-3">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
          <span class="text-gray-600">Total Posts</span>
          <span class="text-2xl font-bold text-rose-400" id="totalPosts">-</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-gray-600">Total Members</span>
          <span class="text-2xl font-bold text-blue-400" id="totalMembers">-</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Articles Container (Hidden by default) -->
<div id="articlesContainer" class="hidden">
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
          Kumpulan artikel kesehatan dan nutrisi dari expert Eatera. Tips, panduan, dan informasi terpercaya untuk perjalanan diet Anda.
        </p>
        <div class="space-y-2 mb-4">
          <p class="text-sm"><strong>📖 Total Artikel:</strong> <span id="totalArticles" class="text-amber-600">-</span></p>
          <p class="text-sm"><strong>📝 Kategori:</strong> Nutrisi, Diet, Tips Kesehatan</p>
        </div>
        <div class="p-3 bg-amber-100 rounded text-xs text-amber-800 border border-amber-200">
          ✨ Artikel baru setiap minggu dari tim expert kami
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Post -->
<div id="editPostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">✏️ Edit Post</h3>
      <button id="closeEditModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-4">
      <form id="editPostForm" class="space-y-3">
        <textarea 
          id="editPostContent" 
          name="content" 
          placeholder="Edit konten post Anda..." 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none"
          rows="4"
          maxlength="1000"
        ></textarea>
        <div class="flex gap-2">
          <small class="text-gray-500 flex-1">Maksimal 1000 karakter</small>
          <small id="charCount" class="text-gray-500">0/1000</small>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" id="cancelEditBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 py-2 rounded-lg font-semibold transition">Batal</button>
          <button type="submit" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white py-2 rounded-lg font-semibold transition">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Report Post -->
<div id="reportPostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">🚨 Lapor Post</h3>
      <button id="closeReportModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-4">
      <form id="reportPostForm" class="space-y-3">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alasan Laporan</label>
          <select 
            id="reportReason" 
            name="reason" 
            required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
          >
            <option value="">-- Pilih Alasan --</option>
            <option value="Spam/Promosi">🔄 Spam/Promosi</option>
            <option value="Konten Tidak Pantas">🚫 Konten Tidak Pantas</option>
            <option value="Ujaran Kebencian">😡 Ujaran Kebencian</option>
            <option value="Penipuan">⚠️ Penipuan/Misleading</option>
            <option value="Pribadi/Privasi">🔒 Pribadi/Privasi</option>
            <option value="Lainnya">📝 Lainnya</option>
          </select>
        </div>
        <div id="detailsContainer" style="display: none;">
          <label class="block text-sm font-medium text-gray-700 mb-2">Detail Tambahan (Opsional)</label>
          <textarea 
            id="reportDetails" 
            name="details" 
            placeholder="Jelaskan lebih detail..." 
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"
            rows="3"
            maxlength="500"
          ></textarea>
          <small class="text-gray-500 block mt-1">Maksimal 500 karakter</small>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" id="cancelReportBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 py-2 rounded-lg font-semibold transition">Batal</button>
          <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold transition">Lapor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Comment -->
<div id="editCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">✏️ Edit Komentar</h3>
      <button id="closeEditCommentModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-4">
      <form id="editCommentForm" class="space-y-3">
        <textarea 
          id="editCommentContent" 
          name="content" 
          placeholder="Edit komentar..." 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
          rows="3"
          maxlength="500"
        ></textarea>
        <div class="flex gap-2">
          <small class="text-gray-500 flex-1">Maksimal 500 karakter</small>
          <small id="editCommentCharCount" class="text-gray-500">0/500</small>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" id="cancelEditCommentBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 py-2 rounded-lg font-semibold transition">Batal</button>
          <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-semibold transition">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Report Comment -->
<div id="reportCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">🚨 Lapor Komentar</h3>
      <button id="closeReportCommentModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-4">
      <form id="reportCommentForm" class="space-y-3">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alasan Laporan</label>
          <select 
            id="reportCommentReason" 
            name="reason" 
            required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
          >
            <option value="">-- Pilih Alasan --</option>
            <option value="Spam/Promosi">🔄 Spam/Promosi</option>
            <option value="Konten Tidak Pantas">🚫 Konten Tidak Pantas</option>
            <option value="Ujaran Kebencian">😡 Ujaran Kebencian</option>
            <option value="Penipuan">⚠️ Penipuan/Misleading</option>
            <option value="Pribadi/Privasi">🔒 Pribadi/Privasi</option>
            <option value="Lainnya">📝 Lainnya</option>
          </select>
        </div>
        <div id="reportCommentDetailsContainer" style="display: none;">
          <label class="block text-sm font-medium text-gray-700 mb-2">Detail Tambahan (Opsional)</label>
          <textarea 
            id="reportCommentDetails" 
            name="details" 
            placeholder="Jelaskan lebih detail..." 
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"
            rows="3"
            maxlength="500"
          ></textarea>
          <small class="text-gray-500 block mt-1">Maksimal 500 karakter</small>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" id="cancelReportCommentBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 py-2 rounded-lg font-semibold transition">Batal</button>
          <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold transition">Lapor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Delete Comment Confirmation -->
<div id="deleteCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4">
    <div class="border-b border-gray-200 p-4">
      <h3 class="text-lg font-semibold text-gray-900">🗑️ Hapus Komentar</h3>
    </div>
    <div class="p-4">
      <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus komentar ini? Aksi ini tidak bisa dibatalkan.</p>
      <div class="flex gap-2">
        <button id="cancelDeleteCommentBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 py-2 rounded-lg font-semibold transition">Batal</button>
        <button id="confirmDeleteCommentBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold transition">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Post Confirmation -->
<div id="deletePostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4 transform transition">
    <div class="bg-gradient-to-r from-red-50 to-pink-50 border-b border-red-200 p-5">
      <h3 class="text-xl font-bold text-red-600 flex items-center gap-2">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        Hapus Postingan
      </h3>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4 text-center leading-relaxed">
        Apakah Anda yakin ingin menghapus postingan ini? <strong>Aksi ini tidak bisa dibatalkan.</strong>
      </p>
      <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6">
        <p class="text-sm text-red-800">⚠️ Data postingan dan semua komentar akan dihapus secara permanen.</p>
      </div>
      <div class="flex gap-3">
        <button id="cancelDeletePostBtn" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2.5 rounded-lg font-semibold transition">Batal</button>
        <button id="confirmDeletePostBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-lg font-semibold transition flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
          Hapus
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="notificationContainer" class="fixed top-4 right-4 z-[100]"></div>

<script>
// FIX: Axios sudah punya baseURL = '/api' di bootstrap.js
// Jadi kita hanya perlu '/community' (axios akan auto-prepend '/api')
const API_BASE = '/community';
let currentUser = null;
let allPosts = [];
let editingPostId = null;
let deletingPostId = null;

// Notification function
function showNotification(message, type = 'info', duration = 4000) {
  const container = document.getElementById('notificationContainer');
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

  const icon = {
    'success': '✅',
    'error': '❌',
    'warning': '⚠️',
    'info': 'ℹ️'
  }[type] || 'ℹ️';

  const notificationEl = document.createElement('div');
  notificationEl.id = notificationId;
  notificationEl.className = `${bgColor} ${borderColor} ${textColor} border rounded-lg shadow-lg p-4 mb-3 flex items-start gap-3 animate-in slide-in-from-right max-w-sm`;
  notificationEl.innerHTML = `
    <span class="text-xl flex-shrink-0">${icon}</span>
    <p class="text-sm font-medium flex-1">${message}</p>
    <button onclick="document.getElementById('${notificationId}').remove()" class="text-lg hover:opacity-50 flex-shrink-0">×</button>
  `;
  
  container.appendChild(notificationEl);

  if (duration > 0) {
    setTimeout(() => {
      const el = document.getElementById(notificationId);
      if (el) {
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => el.remove(), 300);
      }
    }, duration);
  }
}

// Tab switching
window.switchTab = function(tab) {
  console.log('Switching to tab:', tab);
  const postsContainer = document.getElementById('postsContainer');
  const articlesContainer = document.getElementById('articlesContainer');
  const tabPosts = document.getElementById('tabPosts');
  const tabArticles = document.getElementById('tabArticles');
  
  if (tab === 'posts') {
    console.log('Showing posts');
    postsContainer.classList.remove('hidden');
    articlesContainer.classList.add('hidden');
    tabPosts.classList.add('border-rose-500', 'text-rose-500');
    tabPosts.classList.remove('border-transparent', 'text-gray-600');
    tabArticles.classList.remove('border-rose-500', 'text-rose-500');
    tabArticles.classList.add('border-transparent', 'text-gray-600');
  } else if (tab === 'articles') {
    console.log('Showing articles');
    postsContainer.classList.add('hidden');
    articlesContainer.classList.remove('hidden');
    tabArticles.classList.add('border-rose-500', 'text-rose-500');
    tabArticles.classList.remove('border-transparent', 'text-gray-600');
    tabPosts.classList.remove('border-rose-500', 'text-rose-500');
    tabPosts.classList.add('border-transparent', 'text-gray-600');
    console.log('Calling fetchArticles...');
    window.fetchArticles();
  }
}

// Fetch articles
window.fetchArticles = async function() {
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
        ${article.cover_image ? `
          <img src="${article.cover_image}" alt="${article.title}" class="w-full h-48 object-cover rounded-lg mb-4">
        ` : ''}
        <h3 class="text-lg font-semibold text-gray-900 mb-2">${article.title}</h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">${article.content.substring(0, 150)}...</p>
        <a 
          href="/artikel/${article.id}" 
          class="inline-block text-rose-500 hover:text-rose-600 font-semibold text-sm transition"
        >
          Baca Selengkapnya →
        </a>
      </div>
    `).join('');
    
    document.getElementById('articlesList').innerHTML = html;
  } catch (err) {
    console.error('Error fetching articles:', err);
    showNotification('Gagal load artikel', 'error');
  }
}

// View article detail
window.viewArticle = async function(articleId) {
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

// Get current user first
async function getCurrentUser() {
  try {
    const res = await window.axios.get('/user');
    currentUser = res.data;
    console.log('Current user:', currentUser);
  } catch (err) {
    console.error('Error fetching current user:', err);
  }
}

async function fetchPosts() {
  try {
    console.log('Fetching posts from:', `${API_BASE}/posts`);
    const res = await window.axios.get(`${API_BASE}/posts`);
    allPosts = res.data;
    console.log('Posts fetched:', allPosts);
    
    if (allPosts.length === 0) {
      document.getElementById('postsList').innerHTML = `
        <div class="text-center py-12">
          <div class="inline-block">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"></path>
            </svg>
            <p class="text-gray-500 font-medium text-lg">Belum ada post</p>
            <p class="text-gray-400 text-sm">Jadilah yang pertama berbagi pengalaman Anda!</p>
          </div>
        </div>
      `;
      updateStats();
      return;
    }
    
    const postsHtml = allPosts.map(post => createPostElement(post)).join('');
    document.getElementById('postsList').innerHTML = postsHtml;
    
    // Attach event listeners
    attachPostEventListeners();
    attachCommentEventListeners();
    updateStats();
  } catch (err) {
    console.error('Error fetching posts:', err);
    document.getElementById('postsList').innerHTML = `
      <div class="text-center py-12">
        <div class="inline-block">
          <svg class="w-16 h-16 text-red-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-red-600 font-medium text-lg">Gagal memuat posts</p>
          <p class="text-red-400 text-sm">${err.response?.data?.message || err.message}</p>
        </div>
      </div>
    `;
  }
}

function updateStats() {
  document.getElementById('totalPosts').textContent = allPosts.length;
  const uniqueUsers = new Set(allPosts.map(p => p.user_id)).size;
  document.getElementById('totalMembers').textContent = uniqueUsers || '-';
}

function createPostElement(post) {
  const likeCount = post.likes ? post.likes.length : 0;
  const commentCount = post.comments ? post.comments.length : 0;
  
  const userName = post.user?.name || 'Anonymous';
  const createdAt = new Date(post.created_at).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  const isPostOwner = currentUser && currentUser.id === post.user_id;
  const userHasLiked = currentUser && post.likes && post.likes.some(like => like.user_id === currentUser.id);

  return `
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition p-5" data-post-id="${post.id}" data-user-id="${post.user_id}">
      <div class="flex justify-between items-start mb-3">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-rose-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-sm">
            ${userName.charAt(0).toUpperCase()}
          </div>
          <div>
            <p class="font-semibold text-gray-900">${escapeHtml(userName)}</p>
            <p class="text-xs text-gray-500">${createdAt}</p>
          </div>
        </div>
        <div class="dropdown-menu relative">
          <button class="text-gray-400 hover:text-gray-600 text-xl menu-toggle hover:bg-gray-100 rounded-full p-1 w-8 h-8 flex items-center justify-center">⋯</button>
          <div class="dropdown-content hidden absolute right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-20 w-40 py-1">
            ${isPostOwner ? `
              <button class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✏️ Edit</button>
              <button class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">🗑️ Hapus</button>
            ` : `
              <button class="report-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚨 Lapor</button>
            `}
          </div>
        </div>
      </div>

      <p class="text-gray-800 mb-4 text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(post.content)}</p>

      <div class="border-t border-gray-100 pt-3">
        <div class="flex gap-6 mb-3">
          <button class="like-btn flex-1 text-sm text-gray-600 hover:text-rose-400 py-2 like-toggle flex items-center justify-center gap-1 ${userHasLiked ? 'text-rose-400 font-semibold' : ''}" data-post-id="${post.id}">
            <span class="text-lg like-icon">${userHasLiked ? '♥️' : '🤍'}</span>
            <span class="like-count">${likeCount}</span>
          </button>
          <button class="comment-toggle flex-1 text-sm text-gray-600 hover:text-blue-400 py-2 flex items-center justify-center gap-1">
            <span class="text-lg">💬</span>
            <span class="comment-count">${commentCount}</span>
          </button>
        </div>

        <div class="comments-section hidden pt-3 border-t border-gray-100">
          <div class="bg-gray-50 rounded-lg p-3 mb-3 max-h-64 overflow-y-auto comments-list space-y-2">
            ${post.comments && post.comments.length > 0 ? 
              post.comments.map(comment => `
                <div class="bg-white p-2 rounded border border-gray-100" data-comment-id="${comment.id}" data-comment-user-id="${comment.user_id}">
                  <div class="flex items-start gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                      ${(comment.user?.name || 'A').charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 text-xs">
                      <p class="font-semibold text-gray-700">${escapeHtml(comment.user?.name || 'Anonymous')}</p>
                      <p class="text-gray-700 mt-0.5">${escapeHtml(comment.content)}</p>
                      <p class="text-gray-400 text-xs mt-1">${new Date(comment.created_at).toLocaleDateString('id-ID', {month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                      <div class="flex gap-2 mt-2">
                        ${currentUser && currentUser.id === comment.user_id ? `
                          <button class="edit-comment-btn text-blue-500 hover:text-blue-700 text-xs" data-comment-id="${comment.id}">✏️ Edit</button>
                          <button class="delete-comment-btn text-red-500 hover:text-red-700 text-xs" data-comment-id="${comment.id}">🗑️ Hapus</button>
                        ` : `
                          <button class="report-comment-btn text-red-500 hover:text-red-700 text-xs" data-comment-id="${comment.id}" data-post-id="${post.id}">🚨 Lapor</button>
                        `}
                      </div>
                    </div>
                  </div>
                </div>
              `).join('')
              : '<p class="text-xs text-gray-500 text-center py-2">Belum ada komentar</p>'
            }
          </div>
          <form class="comment-form flex gap-2">
            <input 
              type="text" 
              name="comment" 
              placeholder="Komentar..." 
              class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-xs font-semibold shadow-md transition">Kirim</button>
          </form>
        </div>
      </div>
    </div>
  `;
}

function escapeHtml(text) {
  if (!text) return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

function attachPostEventListeners() {
  // Menu toggle
  document.querySelectorAll('.menu-toggle').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      document.querySelectorAll('.dropdown-content').forEach(m => m.classList.add('hidden'));
      const dropdown = btn.closest('.dropdown-menu').querySelector('.dropdown-content');
      dropdown.classList.toggle('hidden');
    });
  });

  // Close menu when clicking outside
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-content').forEach(menu => {
      menu.classList.add('hidden');
    });
  });

  // Like button
  document.querySelectorAll('.like-toggle').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const postId = btn.dataset.postId;
      try {
        await window.axios.post(`${API_BASE}/posts/${postId}/like`);
        fetchPosts();
      } catch (err) {
        console.error('Error liking post:', err);
        showNotification('Gagal like post: ' + (err.response?.data?.message || err.message), 'error');
      }
    });
  });

  // Comment toggle
  document.querySelectorAll('.comment-toggle').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const postEl = btn.closest('[data-post-id]');
      const commentSection = postEl.querySelector('.comments-section');
      commentSection.classList.toggle('hidden');
    });
  });

  // Comment form submit
  document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const postEl = form.closest('[data-post-id]');
      const postId = postEl.dataset.postId;
      const input = form.querySelector('input[name="comment"]');
      const content = input.value;

      if (!content.trim()) {
        showNotification('Komentar tidak boleh kosong', 'warning');
        return;
      }

      try {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Mengirim...';

        await window.axios.post(`${API_BASE}/posts/${postId}/comment`, { content });
        input.value = '';
        
        // Update only this post's comments instead of refreshing all
        const post = allPosts.find(p => p.id == postId);
        if (post) {
          try {
            const res = await window.axios.get(`${API_BASE}/posts`);
            const updatedPost = res.data.find(p => p.id == postId);
            if (updatedPost) {
              post.comments = updatedPost.comments;
              const commentsList = postEl.querySelector('.comments-list');
              commentsList.innerHTML = updatedPost.comments && updatedPost.comments.length > 0 ? 
                updatedPost.comments.map(comment => `
                  <div class="bg-white p-2 rounded border border-gray-100" data-comment-id="${comment.id}" data-comment-user-id="${comment.user_id}">
                    <div class="flex items-start gap-2">
                      <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${(comment.user?.name || 'A').charAt(0).toUpperCase()}
                      </div>
                      <div class="flex-1 text-xs">
                        <p class="font-semibold text-gray-700">${escapeHtml(comment.user?.name || 'Anonymous')}</p>
                        <p class="text-gray-700 mt-0.5">${escapeHtml(comment.content)}</p>
                        <p class="text-gray-400 text-xs mt-1">${new Date(comment.created_at).toLocaleDateString('id-ID', {month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                        <div class="flex gap-2 mt-2">
                          ${currentUser && currentUser.id === comment.user_id ? `
                            <button class="edit-comment-btn text-blue-500 hover:text-blue-700 text-xs" data-comment-id="${comment.id}">✏️ Edit</button>
                            <button class="delete-comment-btn text-red-500 hover:text-red-700 text-xs" data-comment-id="${comment.id}">🗑️ Hapus</button>
                          ` : `
                            <button class="report-comment-btn text-red-500 hover:text-red-700 text-xs" data-comment-id="${comment.id}" data-post-id="${post.id}">🚨 Lapor</button>
                          `}
                        </div>
                      </div>
                    </div>
                  </div>
                `).join('')
                : '<p class="text-xs text-gray-500 text-center py-2">Belum ada komentar</p>';
              
              const commentCount = updatedPost.comments ? updatedPost.comments.length : 0;
              postEl.querySelector('.comment-count').textContent = commentCount;
              
              // Re-attach event listeners to new comment buttons
              attachCommentEventListeners();
            }
          } catch (err) {
            console.error('Error updating comments:', err);
          }
        }

        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      } catch (err) {
        console.error('Error posting comment:', err);
        showNotification('Gagal menambah komentar: ' + (err.response?.data?.message || err.message), 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    });
  });

  // Edit button
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const postEl = btn.closest('[data-post-id]');
      const postId = postEl.dataset.postId;
      const post = allPosts.find(p => p.id == postId);
      
      // Set modal content
      editingPostId = postId;
      document.getElementById('editPostContent').value = post.content;
      updateCharCount();
      
      // Show modal
      document.getElementById('editPostModal').classList.remove('hidden');
    });
  });

  // Delete button
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const postEl = btn.closest('[data-post-id]');
      const postId = postEl.dataset.postId;
      deletingPostId = postId;
      document.getElementById('deletePostModal').classList.remove('hidden');
    });
  });

  // Report button
  document.querySelectorAll('.report-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const postEl = btn.closest('[data-post-id]');
      const postId = postEl.dataset.postId;
      
      // Set the post ID in the form
      document.getElementById('reportPostForm').dataset.postId = postId;
      document.getElementById('reportReason').value = '';
      document.getElementById('reportDetails').value = '';
      document.getElementById('detailsContainer').style.display = 'none';
      
      // Show modal
      document.getElementById('reportPostModal').classList.remove('hidden');
    });
  });
}

// Create post
document.getElementById('createPostForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const content = document.getElementById('postContent').value;

  if (!content.trim()) {
    showNotification('Konten tidak boleh kosong', 'warning');
    return;
  }

  try {
    const submitBtn = document.querySelector('#createPostForm button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Mengirim...';

    await window.axios.post(`${API_BASE}/posts`, { content });
    
    showNotification('Post berhasil dikirim! Menunggu persetujuan admin.', 'success');
    document.getElementById('createPostForm').reset();
    submitBtn.disabled = false;
    submitBtn.textContent = originalText;

    fetchPosts();
  } catch (err) {
    console.error('Error creating post:', err);
    showNotification('Gagal membuat post: ' + (err.response?.data?.message || err.message), 'error');
    const submitBtn = document.querySelector('#createPostForm button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = '📤 Posting';
  }
});

// Modal handlers
function updateCharCount() {
  const textarea = document.getElementById('editPostContent');
  const count = textarea.value.length;
  document.getElementById('charCount').textContent = `${count}/1000`;
}

document.getElementById('editPostContent').addEventListener('input', updateCharCount);

document.getElementById('closeEditModal').addEventListener('click', () => {
  document.getElementById('editPostModal').classList.add('hidden');
  editingPostId = null;
});

document.getElementById('cancelEditBtn').addEventListener('click', () => {
  document.getElementById('editPostModal').classList.add('hidden');
  editingPostId = null;
});

document.getElementById('editPostForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const content = document.getElementById('editPostContent').value;

  if (!content.trim()) {
    showNotification('Konten tidak boleh kosong', 'warning');
    return;
  }

  const originalPost = allPosts.find(p => p.id == editingPostId);
  if (content === originalPost.content) {
    showNotification('Tidak ada perubahan', 'info');
    return;
  }

  try {
    await window.axios.put(`${API_BASE}/posts/${editingPostId}`, { content });
    document.getElementById('editPostModal').classList.add('hidden');
    showNotification('Post berhasil diupdate! Menunggu persetujuan admin.', 'success');
    editingPostId = null;
    fetchPosts();
  } catch (err) {
    console.error('Error updating post:', err);
    showNotification('Gagal update post: ' + (err.response?.data?.message || err.message), 'error');
  }
});

// Close modal when clicking outside
document.getElementById('editPostModal').addEventListener('click', (e) => {
  if (e.target.id === 'editPostModal') {
    document.getElementById('editPostModal').classList.add('hidden');
    editingPostId = null;
  }
});

// Report modal handlers
document.getElementById('closeReportModal').addEventListener('click', () => {
  document.getElementById('reportPostModal').classList.add('hidden');
});

document.getElementById('cancelReportBtn').addEventListener('click', () => {
  document.getElementById('reportPostModal').classList.add('hidden');
});

// Show/hide details container based on reason selection
document.getElementById('reportReason').addEventListener('change', (e) => {
  const detailsContainer = document.getElementById('detailsContainer');
  if (e.target.value === 'Lainnya') {
    detailsContainer.style.display = 'block';
    document.getElementById('reportDetails').required = true;
  } else {
    detailsContainer.style.display = 'none';
    document.getElementById('reportDetails').required = false;
  }
});

document.getElementById('reportPostForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const postId = e.target.dataset.postId;
  const reason = document.getElementById('reportReason').value;
  const details = document.getElementById('reportDetails').value;

  if (!reason.trim()) {
    showNotification('Silakan pilih alasan laporan', 'warning');
    return;
  }

  try {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Mengirim...';

    const payload = { reason };
    if (details) {
      payload.details = details;
    }

    await window.axios.post(`${API_BASE}/posts/${postId}/report`, payload);
    
    document.getElementById('reportPostModal').classList.add('hidden');
    showNotification('Post berhasil dilaporkan kepada admin. Terima kasih!', 'success');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Lapor';
  } catch (err) {
    console.error('Error reporting post:', err);
    showNotification('Gagal melapor post: ' + (err.response?.data?.message || err.message), 'error');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Lapor';
  }
});

// Close modal when clicking outside
document.getElementById('reportPostModal').addEventListener('click', (e) => {
  if (e.target.id === 'reportPostModal') {
    document.getElementById('reportPostModal').classList.add('hidden');
  }
});

// Delete post modal handlers
document.getElementById('deletePostModal').addEventListener('click', (e) => {
  if (e.target.id === 'deletePostModal') {
    document.getElementById('deletePostModal').classList.add('hidden');
    deletingPostId = null;
  }
});

document.getElementById('cancelDeletePostBtn').addEventListener('click', () => {
  document.getElementById('deletePostModal').classList.add('hidden');
  deletingPostId = null;
});

document.getElementById('confirmDeletePostBtn').addEventListener('click', async () => {
  if (!deletingPostId) return;
  
  const btn = document.getElementById('confirmDeletePostBtn');
  btn.disabled = true;

  try {
    await window.axios.delete(`${API_BASE}/posts/${deletingPostId}`);
    document.getElementById('deletePostModal').classList.add('hidden');
    showNotification('Post berhasil dihapus', 'success');
    fetchPosts();
  } catch (err) {
    console.error('Error deleting post:', err);
    showNotification('Gagal hapus post: ' + (err.response?.data?.message || err.message), 'error');
  } finally {
    btn.disabled = false;
    deletingPostId = null;
  }
});

// Comment action handlers
let editingCommentId = null;
let editingPostIdForComment = null;
let deletingCommentId = null;
let deletingPostIdForComment = null;

function attachCommentEventListeners() {
  // Edit comment button
  document.querySelectorAll('.edit-comment-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const commentEl = btn.closest('[data-comment-id]');
      const commentId = commentEl.dataset.commentId;
      const postEl = btn.closest('[data-post-id]');
      const postId = postEl.dataset.postId;
      
      const post = allPosts.find(p => p.id == postId);
      const comment = post.comments.find(c => c.id == commentId);
      
      editingCommentId = commentId;
      editingPostIdForComment = postId;
      document.getElementById('editCommentContent').value = comment.content;
      updateEditCommentCharCount();
      document.getElementById('editCommentModal').classList.remove('hidden');
    });
  });

  // Delete comment button
  document.querySelectorAll('.delete-comment-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      const commentEl = btn.closest('[data-comment-id]');
      const commentId = commentEl.dataset.commentId;
      const postEl = btn.closest('[data-post-id]');
      const postId = postEl.dataset.postId;

      deletingCommentId = commentId;
      deletingPostIdForComment = postId;
      document.getElementById('deleteCommentModal').classList.remove('hidden');
    });
  });

  // Report comment button
  document.querySelectorAll('.report-comment-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const commentEl = btn.closest('[data-comment-id]');
      const commentId = commentEl.dataset.commentId;
      const postId = btn.dataset.postId;
      
      document.getElementById('reportCommentForm').dataset.commentId = commentId;
      document.getElementById('reportCommentForm').dataset.postId = postId;
      document.getElementById('reportCommentReason').value = '';
      document.getElementById('reportCommentDetails').value = '';
      document.getElementById('reportCommentDetailsContainer').style.display = 'none';
      document.getElementById('reportCommentModal').classList.remove('hidden');
    });
  });
}

// Update comment character count
function updateEditCommentCharCount() {
  const textarea = document.getElementById('editCommentContent');
  const count = textarea.value.length;
  document.getElementById('editCommentCharCount').textContent = `${count}/500`;
}

// Edit comment modal handlers
document.getElementById('editCommentContent').addEventListener('input', updateEditCommentCharCount);

document.getElementById('closeEditCommentModal').addEventListener('click', () => {
  document.getElementById('editCommentModal').classList.add('hidden');
  editingCommentId = null;
  editingPostIdForComment = null;
});

document.getElementById('cancelEditCommentBtn').addEventListener('click', () => {
  document.getElementById('editCommentModal').classList.add('hidden');
  editingCommentId = null;
  editingPostIdForComment = null;
});

document.getElementById('editCommentForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const content = document.getElementById('editCommentContent').value;

  if (!content.trim()) {
    showNotification('Komentar tidak boleh kosong', 'warning');
    return;
  }

  try {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Menyimpan...';

    await window.axios.put(`${API_BASE}/posts/${editingPostIdForComment}/comment/${editingCommentId}`, { content });
    
    document.getElementById('editCommentModal').classList.add('hidden');
    showNotification('Komentar berhasil diupdate', 'success');
    editingCommentId = null;
    editingPostIdForComment = null;
    submitBtn.disabled = false;
    submitBtn.textContent = 'Simpan';
    
    fetchPosts();
  } catch (err) {
    console.error('Error updating comment:', err);
    showNotification('Gagal update komentar: ' + (err.response?.data?.message || err.message), 'error');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Simpan';
  }
});

// Close edit comment modal when clicking outside
document.getElementById('editCommentModal').addEventListener('click', (e) => {
  if (e.target.id === 'editCommentModal') {
    document.getElementById('editCommentModal').classList.add('hidden');
    editingCommentId = null;
    editingPostIdForComment = null;
  }
});

// Report comment modal handlers
document.getElementById('closeReportCommentModal').addEventListener('click', () => {
  document.getElementById('reportCommentModal').classList.add('hidden');
});

document.getElementById('cancelReportCommentBtn').addEventListener('click', () => {
  document.getElementById('reportCommentModal').classList.add('hidden');
});

document.getElementById('reportCommentReason').addEventListener('change', (e) => {
  const detailsContainer = document.getElementById('reportCommentDetailsContainer');
  if (e.target.value === 'Lainnya') {
    detailsContainer.style.display = 'block';
    document.getElementById('reportCommentDetails').required = true;
  } else {
    detailsContainer.style.display = 'none';
    document.getElementById('reportCommentDetails').required = false;
  }
});

document.getElementById('reportCommentForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const commentId = e.target.dataset.commentId;
  const postId = e.target.dataset.postId;
  const reason = document.getElementById('reportCommentReason').value;
  const details = document.getElementById('reportCommentDetails').value;

  if (!reason.trim()) {
    showNotification('Silakan pilih alasan laporan', 'warning');
    return;
  }

  try {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Mengirim...';

    const payload = { reason };
    if (details) {
      payload.details = details;
    }

    await window.axios.post(`${API_BASE}/posts/${postId}/comment/${commentId}/report`, payload);
    
    document.getElementById('reportCommentModal').classList.add('hidden');
    showNotification('Komentar berhasil dilaporkan kepada admin. Terima kasih!', 'success');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Lapor';
  } catch (err) {
    console.error('Error reporting comment:', err);
    showNotification('Gagal melapor komentar: ' + (err.response?.data?.message || err.message), 'error');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Lapor';
  }
});

// Close report comment modal when clicking outside
document.getElementById('reportCommentModal').addEventListener('click', (e) => {
  if (e.target.id === 'reportCommentModal') {
    document.getElementById('reportCommentModal').classList.add('hidden');
  }
});

// Delete comment confirmation modal handlers
document.getElementById('cancelDeleteCommentBtn').addEventListener('click', () => {
  document.getElementById('deleteCommentModal').classList.add('hidden');
  deletingCommentId = null;
  deletingPostIdForComment = null;
});

document.getElementById('confirmDeleteCommentBtn').addEventListener('click', async () => {
  if (!deletingCommentId || !deletingPostIdForComment) return;

  try {
    const btn = document.getElementById('confirmDeleteCommentBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Menghapus...';

    await window.axios.delete(`${API_BASE}/posts/${deletingPostIdForComment}/comment/${deletingCommentId}`);
    
    document.getElementById('deleteCommentModal').classList.add('hidden');
    showNotification('Komentar berhasil dihapus', 'success');
    deletingCommentId = null;
    deletingPostIdForComment = null;
    btn.disabled = false;
    btn.textContent = 'Hapus';
    
    fetchPosts();
  } catch (err) {
    console.error('Error deleting comment:', err);
    showNotification('Gagal menghapus komentar: ' + (err.response?.data?.message || err.message), 'error');
    const btn = document.getElementById('confirmDeleteCommentBtn');
    btn.disabled = false;
    btn.textContent = 'Hapus';
  }
});

// Close delete comment modal when clicking outside
document.getElementById('deleteCommentModal').addEventListener('click', (e) => {
  if (e.target.id === 'deleteCommentModal') {
    document.getElementById('deleteCommentModal').classList.add('hidden');
    deletingCommentId = null;
    deletingPostIdForComment = null;
  }
});

// Wait for axios to be ready
function waitForAxios() {
  return new Promise((resolve) => {
    if (window.axios) {
      console.log('Axios ready');
      resolve();
    } else {
      console.log('Waiting for axios...');
      const checkInterval = setInterval(() => {
        if (window.axios) {
          console.log('Axios is now ready');
          clearInterval(checkInterval);
          resolve();
        }
      }, 100);
      // Fallback timeout after 5 seconds
      setTimeout(() => {
        clearInterval(checkInterval);
        console.warn('Axios not available after 5s, proceeding anyway');
        resolve();
      }, 5000);
    }
  });
}

// Initialize
async function initializeCommunity() {
  try {
    await waitForAxios();
    console.log('Starting community initialization');
    await getCurrentUser();
    fetchPosts();
    
    // Check if there's a tab parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam === 'articles') {
      console.log('Auto-switching to articles tab from URL parameter');
      setTimeout(() => {
        window.switchTab('articles');
      }, 500);
    }
    
    // Refresh posts setiap 15 detik, tapi hanya jika user tidak sedang berinteraksi
    setInterval(() => {
      const commentSections = document.querySelectorAll('.comments-section:not(.hidden)');
      const editPostModal = document.getElementById('editPostModal');
      const reportPostModal = document.getElementById('reportPostModal');
      const editCommentModal = document.getElementById('editCommentModal');
      const reportCommentModal = document.getElementById('reportCommentModal');
      const deleteCommentModal = document.getElementById('deleteCommentModal');
      
      // Jangan refresh jika ada modal terbuka atau ada comment section yang aktif
      if (editPostModal.classList.contains('hidden') && 
          reportPostModal.classList.contains('hidden') && 
          editCommentModal.classList.contains('hidden') && 
          reportCommentModal.classList.contains('hidden') && 
          deleteCommentModal.classList.contains('hidden') && 
          commentSections.length === 0) {
        fetchPosts();
      }
    }, 15000);
  } catch (err) {
    console.error('Error initializing community:', err);
  }
}

// Start initialization when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeCommunity);
} else {
  initializeCommunity();
}
</script>

@endsection