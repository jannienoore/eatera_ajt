<!-- Floating Chatbot Widget -->
<div id="chatbotWidget" class="fixed bottom-6 right-6 z-50">
  <!-- Chatbot Floating Button -->
  <button id="chatbotToggleBtn" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all hover:scale-110 flex items-center justify-center w-16 h-16">
    🥨
  </button>

  <!-- Chatbot Window (Hidden by default) -->
  <div id="chatbotWindow" class="hidden absolute bottom-24 right-0 w-96 bg-white rounded-lg shadow-2xl flex flex-col border border-gray-200" style="height: 600px;">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-4 rounded-t-lg flex justify-between items-center">
      <div>
        <h3 class="font-bold text-lg">EATERA Assistant</h3>
        <p class="text-xs text-purple-100">Powered by AI</p>
      </div>
      <button id="chatbotCloseBtn" class="text-white text-2xl leading-none hover:text-gray-200">✕</button>
    </div>

    <!-- Messages Area -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 bg-gray-50 space-y-3">
      <div class="flex justify-start">
        <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-xs text-sm">
          👋 Halo! Saya asisten nutrisi Anda. Ada yang bisa saya bantu?
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div class="border-t p-4 bg-white rounded-b-lg">
      <form id="chatForm" class="flex gap-2">
        <input 
          id="chatInput" 
          type="text" 
          placeholder="Ketik pesan..." 
          class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
          autocomplete="off"
        />
        <button 
          type="submit" 
          class="bg-purple-500 text-white rounded-lg px-4 py-2 hover:bg-purple-600 transition-colors font-semibold"
          id="sendBtn"
        >
          Kirim
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  const chatbotToggleBtn = document.getElementById('chatbotToggleBtn');
  const chatbotCloseBtn = document.getElementById('chatbotCloseBtn');
  const chatbotWindow = document.getElementById('chatbotWindow');
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');
  const sendBtn = document.getElementById('sendBtn');

  let isLoading = false;

  // Toggle chatbot window
  chatbotToggleBtn.addEventListener('click', () => {
    chatbotWindow.classList.toggle('hidden');
    if (!chatbotWindow.classList.contains('hidden')) {
      chatInput.focus();
    }
  });

  // Close chatbot window
  chatbotCloseBtn.addEventListener('click', () => {
    chatbotWindow.classList.add('hidden');
  });

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
      <div class="bg-purple-500 text-white px-4 py-2 rounded-lg max-w-xs text-sm">
        ${message}
      </div>
    `;
    chatMessages.appendChild(userMessageDiv);
    chatInput.value = '';

    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
      // Check authentication
      const token = localStorage.getItem('auth_token');
      if (!token) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex justify-start';
        errorDiv.innerHTML = `
          <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg max-w-xs text-sm">
            ⚠️ Silakan login terlebih dahulu
          </div>
        `;
        chatMessages.appendChild(errorDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        isLoading = false;
        sendBtn.disabled = false;
        return;
      }

      // Ensure token is set in axios header
      window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      console.log('Sending chat request:', { message });

      // Send message to API
      const response = await window.axios.post('/chat', {
        message: message
      });

      console.log('Chat response:', response.data);

      const reply = response.data.reply;

      // Add bot response
      const botMessageDiv = document.createElement('div');
      botMessageDiv.className = 'flex justify-start';
      botMessageDiv.innerHTML = `
        <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-xs text-sm">
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
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg max-w-xs text-sm">
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

  // Check authentication on load
  function checkChatbotAuth() {
    const token = localStorage.getItem('auth_token');
    if (token && window.axios) {
      // Chatbot ready
    }
  }

  // Wait for axios to be available
  function waitForAxios() {
    if (window.axios) {
      checkChatbotAuth();
    } else {
      setTimeout(waitForAxios, 100);
    }
  }

  waitForAxios();
</script>

<style>
  #chatbotWindow {
    animation: slideUp 0.3s ease-out;
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  #chatMessages {
    scroll-behavior: smooth;
  }
</style>
