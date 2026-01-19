@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-4xl font-bold mb-2">🥨 Chat dengan EATERA Assistant</h1>
    <p class="text-gray-600">Tanya apapun tentang nutrisi dan diet Anda</p>
  </div>

  <!-- Login Prompt -->
  <div id="loginPrompt" class="bg-white p-8 rounded-lg shadow text-center mb-8 hidden">
    <h2 class="text-2xl font-bold mb-4">Belum Login</h2>
    <p class="text-gray-600 mb-6">Silakan login untuk chat dengan AI assistant</p>
    <div class="flex gap-4 justify-center">
      <a href="/login" class="bg-rose-400 text-white px-6 py-2 rounded font-semibold hover:bg-rose-500">Login</a>
      <a href="/register" class="bg-blue-400 text-white px-6 py-2 rounded font-semibold hover:bg-blue-500">Register</a>
    </div>
  </div>

  <!-- Chat Container -->
  <div id="chatContainer" class="hidden bg-white rounded-lg shadow overflow-hidden flex flex-col" style="height: 600px;">
    <!-- Messages Area -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-6 bg-gray-50 space-y-4">
      <div class="flex justify-start">
        <div class="bg-gray-200 text-gray-800 px-4 py-3 rounded-lg max-w-lg text-sm">
          👋 <strong>Halo!</strong> Saya asisten nutrisi Anda. Ada yang bisa saya bantu tentang diet dan nutrisi Anda?
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div class="border-t p-6 bg-white">
      <form id="chatForm" class="flex gap-3">
        <input 
          id="chatInput" 
          type="text" 
          placeholder="Ketik pesan Anda..." 
          class="flex-1 border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500"
          autocomplete="off"
        />
        <button 
          type="submit" 
          class="bg-purple-500 text-white rounded-lg px-6 py-3 hover:bg-purple-600 transition-colors font-semibold"
          id="sendBtn"
        >
          Kirim
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  const chatContainer = document.getElementById('chatContainer');
  const loginPrompt = document.getElementById('loginPrompt');
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');
  const sendBtn = document.getElementById('sendBtn');

  let isLoading = false;

  function checkAuthentication() {
    return new Promise((resolve) => {
      function check() {
        if (window.axios) {
          const token = localStorage.getItem('auth_token');
          if (token) {
            chatContainer.classList.remove('hidden');
            loginPrompt.classList.add('hidden');
            chatInput.focus();
            resolve(true);
          } else {
            chatContainer.classList.add('hidden');
            loginPrompt.classList.remove('hidden');
            resolve(false);
          }
        } else {
          setTimeout(check, 100);
        }
      }
      check();
    });
  }

  // Send message
  chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const message = chatInput.value.trim();
    if (!message || isLoading) return;

    isLoading = true;
    sendBtn.disabled = true;

    // Add user message to chat
    const userMessageDiv = document.createElement('div');
    userMessageDiv.className = 'flex justify-end';
    userMessageDiv.innerHTML = `
      <div class="bg-purple-500 text-white px-4 py-3 rounded-lg max-w-lg text-sm">
        ${message}
      </div>
    `;
    chatMessages.appendChild(userMessageDiv);
    chatInput.value = '';

    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
      // Send message to API
      const response = await window.axios.post('/chat', {
        message: message
      });

      const reply = response.data.reply;

      // Add bot response
      const botMessageDiv = document.createElement('div');
      botMessageDiv.className = 'flex justify-start';
      botMessageDiv.innerHTML = `
        <div class="bg-gray-200 text-gray-800 px-4 py-3 rounded-lg max-w-lg text-sm">
          ${reply}
        </div>
      `;
      chatMessages.appendChild(botMessageDiv);

      // Scroll to bottom
      chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (error) {
      console.error('Chat error:', error);
      console.error('Error response:', error.response?.data);
      console.error('Error status:', error.response?.status);
      
      let errorMessage = 'Terjadi kesalahan. Coba lagi nanti.';
      if (error.response?.status === 401) {
        errorMessage = '⚠️ Session expired. Silakan login kembali.';
        localStorage.removeItem('auth_token');
        setTimeout(() => window.location.href = '/login', 2000);
      } else if (error.response?.status === 422) {
        errorMessage = '⚠️ Pesan harus diisi.';
      } else if (error.response?.data?.message) {
        errorMessage = `⚠️ ${error.response.data.message}`;
      } else if (error.message) {
        errorMessage = `⚠️ Error: ${error.message}`;
      }

      const errorDiv = document.createElement('div');
      errorDiv.className = 'flex justify-start';
      errorDiv.innerHTML = `
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg max-w-lg text-sm">
          ${errorMessage}
        </div>
      `;
      chatMessages.appendChild(errorDiv);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    } finally {
      isLoading = false;
      sendBtn.disabled = false;
      chatInput.focus();
    }
  });

  // Initialize - check authentication first
  function waitForAxios() {
    if (window.axios) {
      checkAuthentication();
    } else {
      setTimeout(waitForAxios, 100);
    }
  }

  waitForAxios();
</script>

<style>
  #chatMessages {
    scroll-behavior: smooth;
  }
</style>

@endsection
