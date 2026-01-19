@extends('layouts.admin')

@section('content')
<div class="mb-6">
  <h1 class="text-3xl font-bold text-gray-900 mb-2">🛡️ Kelola Komunitas</h1>
  <p class="text-gray-600">Review postingan, approve/reject, dan kelola laporan dari komunitas</p>
</div>

<!-- Tabs Navigation -->
<div class="flex gap-2 mb-6 border-b border-gray-200">
  <button id="tabPending" class="tab-btn px-4 py-3 font-semibold text-rose-600 border-b-2 border-rose-600">
    📋 Postingan Pending
  </button>
  <button id="tabPostReports" class="tab-btn px-4 py-3 font-semibold text-gray-600 hover:text-gray-900">
    🚨 Laporan Postingan
  </button>
  <button id="tabCommentReports" class="tab-btn px-4 py-3 font-semibold text-gray-600 hover:text-gray-900">
    💬 Laporan Komentar
  </button>
</div>

<!-- Pending Posts Tab -->
<div id="contentPending" class="tab-content">
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">📋 Postingan Menunggu Persetujuan</h2>
    <div id="pendingPostsList" class="space-y-4">
      <div class="text-center py-8 text-gray-500">
        <p>Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Post Reports Tab -->
<div id="contentPostReports" class="tab-content hidden">
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">🚨 Laporan Postingan</h2>
    <div id="postReportsList" class="space-y-4">
      <div class="text-center py-8 text-gray-500">
        <p>Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Comment Reports Tab -->
<div id="contentCommentReports" class="tab-content hidden">
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">💬 Laporan Komentar</h2>
    <div id="commentReportsList" class="space-y-4">
      <div class="text-center py-8 text-gray-500">
        <p>Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Post -->
<div id="postDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center sticky top-0 bg-white">
      <h3 class="text-lg font-semibold text-gray-900">📄 Detail Postingan</h3>
      <button id="closePostModal" class="text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div id="postModalContent" class="p-6">
      <!-- Content will be injected here -->
    </div>
  </div>
</div>

<!-- Modal Approve Post -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">✅ Approve Postingan</h3>
      <button class="closeApproveModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menyetujui postingan ini?</p>
      <textarea id="approveNoteInput" placeholder="Catatan (optional)" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm" rows="3"></textarea>
      <div class="flex gap-2">
        <button id="confirmApproveBtn" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded font-semibold transition">✅ Setujui</button>
        <button class="closeApproveModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Reject Post -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">❌ Tolak Postingan</h3>
      <button class="closeRejectModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Berikan alasan penolakan postingan ini:</p>
      <textarea id="rejectReasonInput" placeholder="Alasan penolakan (wajib diisi)" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm" rows="3"></textarea>
      <div class="flex gap-2">
        <button id="confirmRejectBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold transition">❌ Tolak</button>
        <button class="closeRejectModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Post -->
<div id="deletePostModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">🗑️ Hapus Postingan</h3>
      <button class="closeDeletePostModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus postingan ini? Tindakan ini tidak dapat dibatalkan.</p>
      <textarea id="deletePostReasonInput" placeholder="Alasan penghapusan (optional)" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm" rows="3"></textarea>
      <div class="flex gap-2">
        <button id="confirmDeletePostBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold transition">🗑️ Hapus</button>
        <button class="closeDeletePostModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Mark as Handled Post Report -->
<div id="handlePostReportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">📋 Tandai Laporan Selesai</h3>
      <button class="closeHandlePostReportModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Laporan akan dihapus dari daftar laporan aktif.</p>
      <select id="handlePostReportAction" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm">
        <option value="dismissed">Ditolak (tidak ada pelanggaran)</option>
        <option value="post_deleted">Post sudah dihapus</option>
      </select>
      <div class="flex gap-2">
        <button id="confirmHandlePostReportBtn" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded font-semibold transition">✓ Selesai</button>
        <button class="closeHandlePostReportModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Komentar -->
<div id="commentDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center sticky top-0 bg-white">
      <h3 class="text-lg font-semibold text-gray-900">💬 Detail Komentar</h3>
      <button class="closeCommentDetailModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div id="commentDetailModalContent" class="p-6">
      <!-- Content will be injected here -->
    </div>
  </div>
</div>

<!-- Modal Handle Comment Report -->
<div id="handleCommentReportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">📋 Tandai Laporan Selesai</h3>
      <button class="closeHandleCommentReportModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Laporan akan dihapus dari daftar laporan aktif.</p>
      <select id="handleCommentReportAction" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm">
        <option value="dismissed">Ditolak (tidak ada pelanggaran)</option>
        <option value="comment_deleted">Komentar sudah dihapus</option>
      </select>
      <div class="flex gap-2">
        <button id="confirmHandleCommentReportBtn" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded font-semibold transition">✓ Selesai</button>
        <button class="closeHandleCommentReportModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Comment -->
<div id="deleteCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">🗑️ Hapus Komentar</h3>
      <button class="closeDeleteCommentModal text-gray-500 hover:text-gray-700 text-2xl">×</button>
    </div>
    <div class="p-6">
      <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus komentar ini? Tindakan ini tidak dapat dibatalkan.</p>
      <textarea id="deleteCommentReasonInput" placeholder="Alasan penghapusan (optional)" class="w-full p-3 border border-gray-300 rounded mb-4 text-sm" rows="3"></textarea>
      <div class="flex gap-2">
        <button id="confirmDeleteCommentBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold transition">🗑️ Hapus</button>
        <button class="closeDeleteCommentModal flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded font-semibold transition">Batal</button>
      </div>
    </div>
  </div>
</div>

<script type="module">
const API_BASE = '/admin/community';
let currentTab = 'pending';

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.remove('text-rose-600', 'border-b-2', 'border-rose-600');
      b.classList.add('text-gray-600');
    });
    btn.classList.remove('text-gray-600');
    btn.classList.add('text-rose-600', 'border-b-2', 'border-rose-600');
    
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    
    const tabId = btn.id.replace('tab', '');
    currentTab = tabId.toLowerCase();
    document.getElementById('content' + tabId).classList.remove('hidden');
    
    loadData();
  });
});

// Load data based on current tab
async function loadData() {
  try {
    if (currentTab === 'pending') {
      await loadPendingPosts();
    } else if (currentTab === 'postreports') {
      await loadPostReports();
    } else if (currentTab === 'commentreports') {
      await loadCommentReports();
    }
  } catch (err) {
    console.error('Error loading data:', err);
  }
}

async function loadPendingPosts() {
  try {
    const res = await window.axios.get(`${API_BASE}/posts/pending`);
    const posts = res.data;
    
    if (posts.length === 0) {
      document.getElementById('pendingPostsList').innerHTML = `
        <div class="text-center py-8 text-gray-500">
          <p>✅ Tidak ada postingan yang menunggu persetujuan</p>
        </div>
      `;
      return;
    }
    
    const html = posts.map(post => createPendingPostCard(post)).join('');
    document.getElementById('pendingPostsList').innerHTML = html;
    attachPendingPostListeners();
  } catch (err) {
    console.error('Error loading pending posts:', err);
    document.getElementById('pendingPostsList').innerHTML = `
      <div class="text-center py-8 text-red-500">
        <p>Gagal memuat postingan pending</p>
      </div>
    `;
  }
}

function createPendingPostCard(post) {
  const userName = post.user?.name || 'Unknown';
  const createdAt = new Date(post.created_at).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  return `
    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" data-post-id="${post.id}">
      <div class="flex justify-between items-start mb-3">
        <div>
          <p class="font-semibold text-gray-900">${escapeHtml(userName)}</p>
          <p class="text-sm text-gray-500">${createdAt}</p>
        </div>
        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">⏳ Pending</span>
      </div>
      
      <p class="text-gray-800 mb-4 text-sm leading-relaxed whitespace-pre-wrap line-clamp-3">${escapeHtml(post.content)}</p>
      
      <div class="flex gap-2">
        <button class="view-post-btn flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded font-semibold transition">👁️ Lihat Detail</button>
        <button class="approve-btn flex-1 bg-green-50 hover:bg-green-100 text-green-600 py-2 rounded font-semibold transition">✅ Approve</button>
        <button class="reject-btn flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded font-semibold transition">❌ Reject</button>
      </div>
    </div>
  `;
}

function attachPendingPostListeners() {
  document.querySelectorAll('.view-post-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const postId = btn.closest('[data-post-id]').dataset.postId;
      const post = (async () => {
        const res = await window.axios.get(`${API_BASE}/posts/pending`);
        return res.data.find(p => p.id == postId);
      })().then(post => showPostDetail(post));
    });
  });

  document.querySelectorAll('.approve-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const postId = btn.closest('[data-post-id]').dataset.postId;
      const postCard = btn.closest('[data-post-id]');
      
      document.getElementById('approveNoteInput').value = '';
      document.getElementById('approveModal').classList.remove('hidden');
      document.getElementById('approveModal').dataset.postId = postId;
    });
  });

  document.querySelectorAll('.reject-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const postId = btn.closest('[data-post-id]').dataset.postId;
      
      document.getElementById('rejectReasonInput').value = '';
      document.getElementById('rejectModal').classList.remove('hidden');
      document.getElementById('rejectModal').dataset.postId = postId;
    });
  });
}

// Approve Post Modal
document.getElementById('confirmApproveBtn').addEventListener('click', async () => {
  const postId = document.getElementById('approveModal').dataset.postId;
  const note = document.getElementById('approveNoteInput').value;
  
  try {
    await window.axios.post(`${API_BASE}/posts/${postId}/approve`);
    showSuccessMessage('✅ Postingan berhasil disetujui');
    document.getElementById('approveModal').classList.add('hidden');
    loadPendingPosts();
  } catch (err) {
    showErrorMessage('❌ Gagal approve: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeApproveModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('approveModal').classList.add('hidden');
  });
});

// Reject Post Modal
document.getElementById('confirmRejectBtn').addEventListener('click', async () => {
  const postId = document.getElementById('rejectModal').dataset.postId;
  const reason = document.getElementById('rejectReasonInput').value;
  
  if (!reason.trim()) {
    showErrorMessage('⚠️ Alasan penolakan harus diisi');
    return;
  }
  
  try {
    await window.axios.post(`${API_BASE}/posts/${postId}/reject`);
    showSuccessMessage('✅ Postingan ditolak');
    document.getElementById('rejectModal').classList.add('hidden');
    loadPendingPosts();
  } catch (err) {
    showErrorMessage('❌ Gagal reject: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeRejectModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('rejectModal').classList.add('hidden');
  });
});

async function loadPostReports() {
  try {
    const res = await window.axios.get(`${API_BASE}/reports/posts`);
    const reports = res.data;
    
    if (reports.length === 0) {
      document.getElementById('postReportsList').innerHTML = `
        <div class="text-center py-8 text-gray-500">
          <p>✅ Tidak ada laporan postingan</p>
        </div>
      `;
      return;
    }
    
    const html = reports.map(report => createPostReportCard(report)).join('');
    document.getElementById('postReportsList').innerHTML = html;
    attachPostReportListeners();
  } catch (err) {
    console.error('Error loading post reports:', err);
    document.getElementById('postReportsList').innerHTML = `
      <div class="text-center py-8 text-red-500">
        <p>Gagal memuat laporan postingan</p>
      </div>
    `;
  }
}

function createPostReportCard(report) {
  const reporterName = report.reporter?.name || 'Unknown';
  const postContent = report.post?.content || 'Post tidak ditemukan';
  const postId = report.post?.id || null;
  const createdAt = new Date(report.created_at).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  return `
    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" data-report-id="${report.id}" data-post-id="${postId}">
      <div class="flex justify-between items-start mb-3">
        <div>
          <p class="font-semibold text-gray-900">📤 Dilaporkan oleh: ${escapeHtml(reporterName)}</p>
          <p class="text-sm text-gray-500">${createdAt}</p>
        </div>
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">🚨 Laporan</span>
      </div>
      
      <div class="bg-gray-50 p-3 rounded mb-3">
        <p class="text-xs font-semibold text-gray-700 mb-1">Alasan:</p>
        <p class="text-sm text-gray-700">${escapeHtml(report.reason)}</p>
      </div>
      
      <div class="bg-yellow-50 p-3 rounded mb-3">
        <p class="text-xs font-semibold text-gray-700 mb-1">Post yang dilaporkan:</p>
        <p class="text-sm text-gray-700 line-clamp-2">${escapeHtml(postContent)}</p>
      </div>
      
      <div class="flex gap-2">
        <button class="delete-reported-btn flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded font-semibold transition">🗑️ Hapus Post</button>
        <button class="dismiss-report-btn flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-2 rounded font-semibold transition">📋 Mark as Handled</button>
      </div>
    </div>
  `;
}

function attachPostReportListeners() {
  document.querySelectorAll('.delete-reported-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const reportCard = btn.closest('[data-report-id]');
      const postId = reportCard.getAttribute('data-post-id');
      
      document.getElementById('deletePostModal').dataset.postId = postId;
      document.getElementById('deletePostModal').dataset.reportId = reportCard.getAttribute('data-report-id');
      document.getElementById('deletePostReasonInput').value = '';
      document.getElementById('deletePostModal').classList.remove('hidden');
    });
  });

  document.querySelectorAll('.dismiss-report-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const reportId = btn.closest('[data-report-id]').dataset.reportId;
      
      document.getElementById('handlePostReportModal').dataset.reportId = reportId;
      document.getElementById('handlePostReportAction').value = 'dismissed';
      document.getElementById('handlePostReportModal').classList.remove('hidden');
    });
  });
}

// Delete Post Modal
document.getElementById('confirmDeletePostBtn').addEventListener('click', async () => {
  const postId = document.getElementById('deletePostModal').dataset.postId;
  const reportId = document.getElementById('deletePostModal').dataset.reportId;
  const reason = document.getElementById('deletePostReasonInput').value;
  
  try {
    await window.axios.delete(`${API_BASE}/posts/${postId}`);
    showSuccessMessage('✅ Postingan berhasil dihapus');
    document.getElementById('deletePostModal').classList.add('hidden');
    
    // Delete the report too
    try {
      await window.axios.delete(`${API_BASE}/reports/posts/${reportId}`);
    } catch (e) {}
    
    loadPostReports();
  } catch (err) {
    showErrorMessage('❌ Gagal hapus: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeDeletePostModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('deletePostModal').classList.add('hidden');
  });
});

// Handle Post Report Modal
document.getElementById('confirmHandlePostReportBtn').addEventListener('click', async () => {
  const reportId = document.getElementById('handlePostReportModal').dataset.reportId;
  const action = document.getElementById('handlePostReportAction').value;
  
  try {
    await window.axios.delete(`${API_BASE}/reports/posts/${reportId}`);
    showSuccessMessage('✅ Laporan berhasil ditandai selesai');
    document.getElementById('handlePostReportModal').classList.add('hidden');
    loadPostReports();
  } catch (err) {
    showErrorMessage('❌ Gagal tandai selesai: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeHandlePostReportModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('handlePostReportModal').classList.add('hidden');
  });
});

async function loadCommentReports() {
  try {
    const res = await window.axios.get(`${API_BASE}/reports/comments`);
    const reports = res.data;
    
    if (reports.length === 0) {
      document.getElementById('commentReportsList').innerHTML = `
        <div class="text-center py-8 text-gray-500">
          <p>✅ Tidak ada laporan komentar</p>
        </div>
      `;
      return;
    }
    
    const html = reports.map(report => createCommentReportCard(report)).join('');
    document.getElementById('commentReportsList').innerHTML = html;
    attachCommentReportListeners();
  } catch (err) {
    console.error('Error loading comment reports:', err);
    document.getElementById('commentReportsList').innerHTML = `
      <div class="text-center py-8 text-red-500">
        <p>Gagal memuat laporan komentar</p>
      </div>
    `;
  }
}

function createCommentReportCard(report) {
  const reporterName = report.reporter?.name || 'Unknown';
  const commentAuthorName = report.comment?.user?.name || 'Unknown';
  const commentContent = report.comment?.content || 'Komentar tidak ditemukan';
  const commentId = report.comment?.id || null;
  const postId = report.post?.id || null;
  const createdAt = new Date(report.created_at).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  return `
    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" data-report-id="${report.id}" data-comment-id="${commentId}" data-post-id="${postId}">
      <div class="flex justify-between items-start mb-3">
        <div>
          <p class="font-semibold text-gray-900">💬 Komentar dari ${escapeHtml(commentAuthorName)}</p>
          <p class="text-sm text-gray-500">Dilaporkan oleh ${escapeHtml(reporterName)} - ${createdAt}</p>
        </div>
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">🚨 Laporan</span>
      </div>
      
      <div class="bg-gray-50 p-3 rounded mb-3">
        <p class="text-xs font-semibold text-gray-700 mb-1">Alasan:</p>
        <p class="text-sm text-gray-700">${escapeHtml(report.reason)}</p>
      </div>
      
      <div class="bg-yellow-50 p-3 rounded mb-3">
        <p class="text-xs font-semibold text-gray-700 mb-1">Komentar yang dilaporkan:</p>
        <p class="text-sm text-gray-700 line-clamp-2">${escapeHtml(commentContent)}</p>
      </div>
      
      <div class="flex gap-2">
        <button class="view-comment-btn flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded font-semibold transition">👁️ Lihat Detail</button>
        <button class="delete-comment-report-btn flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded font-semibold transition">🗑️ Hapus Komentar</button>
        <button class="handle-comment-report-btn flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 py-2 rounded font-semibold transition">📋 Handle</button>
      </div>
    </div>
  `;
}

function attachCommentReportListeners() {
  document.querySelectorAll('.view-comment-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const reportCard = btn.closest('[data-report-id]');
      const reportId = reportCard.getAttribute('data-report-id');
      
      // Find the report data
      window.axios.get(`${API_BASE}/reports/comments`).then(res => {
        const report = res.data.find(r => r.id == reportId);
        if (report) {
          showCommentDetail(report);
        }
      });
    });
  });

  document.querySelectorAll('.delete-comment-report-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const reportCard = btn.closest('[data-report-id]');
      const commentId = reportCard.getAttribute('data-comment-id');
      const postId = reportCard.getAttribute('data-post-id');
      const reportId = reportCard.getAttribute('data-report-id');
      
      document.getElementById('deleteCommentModal').dataset.commentId = commentId;
      document.getElementById('deleteCommentModal').dataset.postId = postId;
      document.getElementById('deleteCommentModal').dataset.reportId = reportId;
      document.getElementById('deleteCommentReasonInput').value = '';
      document.getElementById('deleteCommentModal').classList.remove('hidden');
    });
  });

  document.querySelectorAll('.handle-comment-report-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const reportId = btn.closest('[data-report-id]').dataset.reportId;
      
      document.getElementById('handleCommentReportModal').dataset.reportId = reportId;
      document.getElementById('handleCommentReportAction').value = 'dismissed';
      document.getElementById('handleCommentReportModal').classList.remove('hidden');
    });
  });
}

function showCommentDetail(report) {
  const commentAuthor = report.comment?.user?.name || 'Unknown';
  const commentContent = report.comment?.content || 'Komentar tidak ditemukan';
  const reporterName = report.reporter?.name || 'Unknown';
  const reportReason = report.reason || 'Tidak ada alasan';
  const createdAt = new Date(report.created_at).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });

  const content = `
    <div class="space-y-4">
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">PENULIS KOMENTAR</p>
        <p class="text-lg font-semibold">${escapeHtml(commentAuthor)}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">ISI KOMENTAR</p>
        <p class="text-sm leading-relaxed bg-gray-50 p-3 rounded">${escapeHtml(commentContent)}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">DILAPORKAN OLEH</p>
        <p class="text-sm">${escapeHtml(reporterName)}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">ALASAN LAPORAN</p>
        <p class="text-sm">${escapeHtml(reportReason)}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">WAKTU LAPORAN</p>
        <p class="text-sm">${createdAt}</p>
      </div>
    </div>
  `;
  document.getElementById('commentDetailModalContent').innerHTML = content;
  document.getElementById('commentDetailModal').classList.remove('hidden');
}

// Delete Comment Modal
document.getElementById('confirmDeleteCommentBtn').addEventListener('click', async () => {
  const commentId = document.getElementById('deleteCommentModal').dataset.commentId;
  const postId = document.getElementById('deleteCommentModal').dataset.postId;
  const reportId = document.getElementById('deleteCommentModal').dataset.reportId;
  
  try {
    console.log(`Deleting comment: postId=${postId}, commentId=${commentId}`);
    await window.axios.delete(`${API_BASE}/posts/${postId}/comment/${commentId}`);
    showSuccessMessage('✅ Komentar berhasil dihapus');
    document.getElementById('deleteCommentModal').classList.add('hidden');
    
    // Delete the report too
    try {
      await window.axios.delete(`${API_BASE}/reports/comments/${reportId}`);
    } catch (e) {}
    
    loadCommentReports();
  } catch (err) {
    console.error('Delete error:', err);
    showErrorMessage('❌ Gagal hapus: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeDeleteCommentModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('deleteCommentModal').classList.add('hidden');
  });
});

// Handle Comment Report Modal
document.getElementById('confirmHandleCommentReportBtn').addEventListener('click', async () => {
  const reportId = document.getElementById('handleCommentReportModal').dataset.reportId;
  const action = document.getElementById('handleCommentReportAction').value;
  
  try {
    await window.axios.delete(`${API_BASE}/reports/comments/${reportId}`);
    showSuccessMessage('✅ Laporan berhasil ditandai selesai');
    document.getElementById('handleCommentReportModal').classList.add('hidden');
    loadCommentReports();
  } catch (err) {
    showErrorMessage('❌ Gagal tandai selesai: ' + (err.response?.data?.message || err.message));
  }
});

document.querySelectorAll('.closeHandleCommentReportModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('handleCommentReportModal').classList.add('hidden');
  });
});

document.querySelectorAll('.closeCommentDetailModal').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('commentDetailModal').classList.add('hidden');
  });
});

document.getElementById('commentDetailModal').addEventListener('click', (e) => {
  if (e.target.id === 'commentDetailModal') {
    document.getElementById('commentDetailModal').classList.add('hidden');
  }
});

function showPostDetail(post) {
  const content = `
    <div class="space-y-4">
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">AUTHOR</p>
        <p class="text-lg font-semibold">${escapeHtml(post.user?.name || 'Unknown')}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">TANGGAL</p>
        <p class="text-sm">${new Date(post.created_at).toLocaleDateString('id-ID', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })}</p>
      </div>
      <div>
        <p class="text-xs font-semibold text-gray-600 mb-1">ISI POST</p>
        <p class="text-sm leading-relaxed whitespace-pre-wrap bg-gray-50 p-3 rounded">${escapeHtml(post.content)}</p>
      </div>
    </div>
  `;
  document.getElementById('postModalContent').innerHTML = content;
  document.getElementById('postDetailModal').classList.remove('hidden');
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

function showSuccessMessage(message) {
  const messageEl = document.createElement('div');
  messageEl.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[100] animate-pulse';
  messageEl.textContent = message;
  document.body.appendChild(messageEl);
  
  setTimeout(() => {
    messageEl.remove();
  }, 3000);
}

function showErrorMessage(message) {
  const messageEl = document.createElement('div');
  messageEl.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[100] animate-pulse';
  messageEl.textContent = message;
  document.body.appendChild(messageEl);
  
  setTimeout(() => {
    messageEl.remove();
  }, 3000);
}

document.getElementById('closePostModal').addEventListener('click', () => {
  document.getElementById('postDetailModal').classList.add('hidden');
});

document.getElementById('postDetailModal').addEventListener('click', (e) => {
  if (e.target.id === 'postDetailModal') {
    document.getElementById('postDetailModal').classList.add('hidden');
  }
});

// Initial load
loadPendingPosts();
</script>

@endsection
