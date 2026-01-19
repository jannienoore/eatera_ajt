@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
  <h1 class="text-3xl font-bold">📚 Kelola Artikel</h1>
  <button 
    id="createArticleBtn" 
    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition"
  >
    ✏️ Buat Artikel Baru
  </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <p class="text-blue-600 text-sm font-semibold">Total Artikel</p>
    <p class="text-3xl font-bold text-blue-700" id="totalArticles">0</p>
  </div>
  <div class="bg-green-50 border border-green-200 rounded-lg p-4">
    <p class="text-green-600 text-sm font-semibold">Published</p>
    <p class="text-3xl font-bold text-green-700" id="publishedArticles">0</p>
  </div>
  <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <p class="text-yellow-600 text-sm font-semibold">Draft</p>
    <p class="text-3xl font-bold text-yellow-700" id="draftArticles">0</p>
  </div>
</div>

<!-- Articles Table -->
<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Penulis</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
          <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
        </tr>
      </thead>
      <tbody id="articlesTableBody" class="divide-y divide-gray-200">
        <tr>
          <td colspan="5" class="px-6 py-8 text-center text-gray-500">
            <div class="inline-block">
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"></path>
              </svg>
              <p>Loading articles...</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Create/Edit Article Modal -->
<div id="articleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-start">
      <h2 class="text-2xl font-bold text-gray-900" id="modalTitle">Buat Artikel Baru</h2>
      <button onclick="closeArticleModal()" class="text-gray-500 hover:text-gray-700 text-2xl">✕</button>
    </div>
    
    <form id="articleForm" class="p-6 space-y-4">
      <div>
        <label class="block text-sm font-semibold text-gray-900 mb-2">Judul Artikel</label>
        <input 
          id="articleTitle" 
          type="text" 
          name="title" 
          placeholder="Masukkan judul artikel..." 
          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-900 mb-2">Konten Artikel</label>
        <textarea 
          id="articleContent" 
          name="content" 
          placeholder="Tulis konten artikel Anda di sini..." 
          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
          rows="10"
          required
        ></textarea>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
        <select 
          id="articleStatus" 
          name="status" 
          class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
          <option value="draft">📝 Draft</option>
          <option value="published">✅ Published</option>
        </select>
      </div>

      <div id="formMessage" class="mt-4 text-sm font-medium" style="display:none;"></div>

      <div class="flex gap-3 pt-4 border-t border-gray-200">
        <button 
          type="button" 
          onclick="closeArticleModal()" 
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition"
        >
          Batal
        </button>
        <button 
          type="submit" 
          class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition"
        >
          Simpan Artikel
        </button>
      </div>
    </form>
  </div>
</div>

<script>
const API_BASE = '/admin/articles';
let articles = [];
let editingArticleId = null;

// Notification functions
function showAdminNotification(message, type = 'info') {
  const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
  const messageEl = document.createElement('div');
  messageEl.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-[100]`;
  messageEl.textContent = message;
  document.body.appendChild(messageEl);
  
  setTimeout(() => {
    messageEl.remove();
  }, 3000);
}

// Fetch articles
async function fetchArticles() {
  try {
    console.log('Fetching articles from:', API_BASE);
    const startTime = performance.now();
    const res = await window.axios.get(API_BASE);
    articles = res.data;
    const loadTime = performance.now() - startTime;
    
    console.log(`Articles loaded in ${loadTime.toFixed(2)}ms:`, articles.length, 'items');
    
    updateStats();
    renderTable();
  } catch (err) {
    console.error('Error fetching articles:', err);
    
    // Handle unauthorized - redirect to login
    if (err.response?.status === 401) {
      showAdminNotification('Sesi Anda telah berakhir. Silakan login kembali.', 'error');
      setTimeout(() => {
        window.location.href = '/login';
      }, 1500);
      return;
    }
    
    // Handle forbidden (user is not admin)
    if (err.response?.status === 403) {
      showAdminNotification('Anda tidak memiliki akses. Hanya admin yang diizinkan.', 'error');
      setTimeout(() => {
        window.location.href = '/';
      }, 1500);
      return;
    }
    
    showAdminNotification('Gagal load artikel: ' + (err.response?.data?.message || err.message), 'error');
  }
}

// Update stats
function updateStats() {
  document.getElementById('totalArticles').textContent = articles.length;
  document.getElementById('publishedArticles').textContent = articles.filter(a => a.status === 'published').length;
  document.getElementById('draftArticles').textContent = articles.filter(a => a.status === 'draft').length;
}

// Render table
function renderTable() {
  if (articles.length === 0) {
    document.getElementById('articlesTableBody').innerHTML = `
      <tr>
        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
          <div class="inline-block">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.76 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9-3.507 9-7.747m0 13c5.5 0 9-3.507 9-7.747"></path>
            </svg>
            <p>Belum ada artikel</p>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  const html = articles.map(article => {
    const statusBadge = article.status === 'published' 
      ? '<span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded">✅ Published</span>'
      : '<span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded">📝 Draft</span>';
    
    const createdDate = new Date(article.created_at).toLocaleDateString('id-ID');
    
    return `
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4">
          <div>
            <p class="font-semibold text-gray-900">${article.title}</p>
            <p class="text-xs text-gray-500 truncate">${article.content.substring(0, 50)}...</p>
          </div>
        </td>
        <td class="px-6 py-4">${statusBadge}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${article.admin?.name || 'Unknown'}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${createdDate}</td>
        <td class="px-6 py-4">
          <div class="flex gap-2 justify-center">
            <button 
              onclick="editArticle(${article.id})" 
              class="text-blue-500 hover:text-blue-700 font-semibold text-sm transition"
              title="Edit"
            >
              ✏️ Edit
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');

  document.getElementById('articlesTableBody').innerHTML = html;
}

// Open create modal
document.getElementById('createArticleBtn').addEventListener('click', () => {
  editingArticleId = null;
  document.getElementById('modalTitle').textContent = 'Buat Artikel Baru';
  document.getElementById('articleForm').reset();
  document.getElementById('articleModal').classList.remove('hidden');
});

// Close modal
function closeArticleModal() {
  document.getElementById('articleModal').classList.add('hidden');
  editingArticleId = null;
}

// Edit article
async function editArticle(id) {
  try {
    const res = await window.axios.get(`${API_BASE}/${id}`);
    const article = res.data;
    
    editingArticleId = id;
    document.getElementById('modalTitle').textContent = '✏️ Edit Artikel';
    document.getElementById('articleTitle').value = article.title;
    document.getElementById('articleContent').value = article.content;
    document.getElementById('articleStatus').value = article.status;
    
    document.getElementById('articleModal').classList.remove('hidden');
  } catch (err) {
    console.error('Error fetching article:', err);
    showAdminNotification('Gagal load artikel', 'error');
  }
}

// Save article (create/update)
document.getElementById('articleForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const title = document.getElementById('articleTitle').value;
  const content = document.getElementById('articleContent').value;
  const status = document.getElementById('articleStatus').value;
  
  const data = { title, content, status };
  
  try {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Menyimpan...';

    if (editingArticleId) {
      await window.axios.put(`${API_BASE}/${editingArticleId}`, data);
      showAdminNotification('✅ Artikel berhasil diupdate', 'success');
    } else {
      await window.axios.post(API_BASE, data);
      showAdminNotification('✅ Artikel berhasil dibuat', 'success');
    }
    
    closeArticleModal();
    fetchArticles();
    
    submitBtn.disabled = false;
    submitBtn.textContent = 'Simpan Artikel';
  } catch (err) {
    console.error('Error saving article:', err);
    showAdminNotification('❌ Gagal simpan: ' + (err.response?.data?.message || err.message), 'error');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Simpan Artikel';
  }
});

// Click outside modal to close
document.getElementById('articleModal').addEventListener('click', (e) => {
  if (e.target.id === 'articleModal') {
    closeArticleModal();
  }
});

// Initialize - fetch articles on page load
document.addEventListener('DOMContentLoaded', () => {
  // Ensure axios is ready
  if (window.axios) {
    fetchArticles();
  } else {
    console.error('Axios not available yet');
    setTimeout(fetchArticles, 500);
  }
});
</script>

@endsection
