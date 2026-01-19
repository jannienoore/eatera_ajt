@extends('layouts.app')

@section('content')
<!-- Back Button -->
<div class="mb-6">
  <a href="/komunitas?tab=articles" class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-600 font-semibold transition">
    ← Kembali ke Komunitas
  </a>
</div>

<!-- Article Container -->
<div class="max-w-3xl mx-auto">
  <!-- Loading State -->
  <div id="loadingState" class="text-center py-12">
    <div class="inline-block">
      <svg class="w-12 h-12 text-gray-400 mx-auto mb-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
      </svg>
      <p class="text-gray-500 font-medium">Loading artikel...</p>
    </div>
  </div>

  <!-- Article Content (Hidden until loaded) -->
  <div id="articleContent" class="hidden">
    <!-- Article Header -->
    <div class="mb-8 pb-8 border-b border-gray-200">
      <h1 id="articleTitle" class="text-4xl font-bold text-gray-900 mb-6 leading-tight"></h1>
      
      <!-- Author & Meta Info -->
      <div class="flex items-center gap-4 flex-wrap">
        <div class="flex items-center gap-3">
          <div id="authorAvatar" class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-400 to-rose-500 flex items-center justify-center text-white font-bold">
            A
          </div>
          <div>
            <p id="authorName" class="font-semibold text-gray-900">✍️ Tim Expert</p>
            <p id="publishDate" class="text-sm text-gray-500">📅 -</p>
          </div>
        </div>
        <div class="flex gap-3 ml-auto">
          <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">📚 Artikel</span>
          <span id="readingTime" class="inline-block px-3 py-1 bg-rose-100 text-rose-800 rounded-full text-xs font-semibold">⏱️ - min</span>
        </div>
      </div>
    </div>

    <!-- Article Content -->
    <div class="prose prose-lg max-w-none mb-12">
      <article id="articleText" class="text-gray-700 leading-relaxed text-justify space-y-6">
        <!-- Content will be inserted here -->
      </article>
    </div>

    <!-- Divider -->
    <div class="my-8 border-t-2 border-gray-200"></div>

    <!-- Footer Section -->
    <div class="bg-gradient-to-r from-rose-50 to-amber-50 rounded-xl p-6 mb-8">
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
    <div class="flex gap-3 pb-8">
      <button id="shareBtn" class="w-full px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-lg transition">
        📤 Bagikan
      </button>
    </div>
  </div>

  <!-- Error State -->
  <div id="errorState" class="hidden text-center py-12">
    <div class="inline-block">
      <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <p class="text-red-500 font-medium mb-4">Gagal memuat artikel</p>
      <a href="/artikel" class="text-rose-500 hover:text-rose-600 font-semibold">
        Kembali ke daftar artikel
      </a>
    </div>
  </div>
</div>

<script>
const API_BASE = '/community';
const articleId = {{ $id }};

async function loadArticle() {
  try {
    // Wait for axios
    if (!window.axios) {
      setTimeout(loadArticle, 100);
      return;
    }

    const res = await window.axios.get(`${API_BASE}/articles/${articleId}`);
    const article = res.data;

    console.log('Article loaded:', article);

    // Update title
    document.getElementById('articleTitle').textContent = article.title;
    document.title = `${article.title} - Eatera`;

    // Update author info
    const authorInitial = article.admin?.name?.charAt(0).toUpperCase() || 'A';
    document.getElementById('authorAvatar').textContent = authorInitial;
    document.getElementById('authorName').innerHTML = `✍️ ${article.admin?.name || 'Tim Expert'}`;

    // Update date
    const publishDate = new Date(article.created_at).toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    document.getElementById('publishDate').innerHTML = `📅 ${publishDate}`;

    // Update reading time
    const wordCount = article.content.split(' ').length;
    const readingTime = Math.ceil(wordCount / 200);
    document.getElementById('readingTime').innerHTML = `⏱️ ${readingTime} min baca`;

    // Update cover image
    if (article.cover_image) {
      const coverImg = document.getElementById('coverImage');
      const placeholderImg = document.getElementById('placeholderImage');
      coverImg.src = article.cover_image;
      coverImg.alt = article.title;
      coverImg.classList.remove('hidden');
      placeholderImg.classList.add('hidden');
    }

    // Format and insert article content
    const contentDiv = document.getElementById('articleText');
    const paragraphs = article.content.split('\n\n').filter(p => p.trim());
    contentDiv.innerHTML = paragraphs.map(p => {
      const formattedP = p.replace(/\n/g, '<br>');
      return `<p class="mb-6">${formattedP}</p>`;
    }).join('');

    // Share button
    document.getElementById('shareBtn').addEventListener('click', () => {
      if (navigator.share) {
        navigator.share({
          title: article.title,
          text: 'Baca artikel menarik ini dari Eatera',
          url: window.location.href
        }).catch(e => console.log('Share cancelled:', e));
      } else {
        // Fallback: copy link
        const url = window.location.href;
        navigator.clipboard.writeText(url);
        alert('Link artikel disalin ke clipboard!');
      }
    });

    // Show content, hide loading
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('articleContent').classList.remove('hidden');

  } catch (err) {
    console.error('Error loading article:', err);
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('errorState').classList.remove('hidden');
  }
}

// Initialize
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', loadArticle);
} else {
  loadArticle();
}
</script>

@endsection
