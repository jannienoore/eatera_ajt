<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eatera - Track Your Meals</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 font-sans">
  <nav class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="/" class="font-bold text-xl text-rose-400">Eatera</a>
      <div class="space-x-4 flex items-center">
        <a href="/login" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
        <a href="/register" class="bg-rose-400 text-white px-4 py-2 rounded text-sm">Register</a>
      </div>
    </div>
  </nav>

  <main>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-rose-50 to-pink-50 py-16 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-5xl font-bold mb-4">Track Your Meals, <span class="text-rose-400">Love Your Health</span></h1>
        <p class="text-xl text-gray-600 mb-8">Monitor your daily nutrition, reach your goals, and build healthier eating habits with Eatera.</p>
        <div class="flex gap-4 justify-center">
          <a href="/register" class="bg-rose-400 text-white px-6 py-3 rounded font-semibold hover:bg-rose-500">Get Started</a>
          <a href="/login" class="border-2 border-rose-400 text-rose-400 px-6 py-3 rounded font-semibold hover:bg-rose-50">Login</a>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Eatera?</h2>
        <div class="grid grid-cols-3 gap-8">
          <div class="bg-rose-50 p-8 rounded-lg">
            <div class="text-4xl mb-4">📊</div>
            <h3 class="text-xl font-bold mb-2">Track Daily Intake</h3>
            <p class="text-gray-600">Log your meals and monitor your daily nutrition intake easily.</p>
          </div>
          <div class="bg-rose-50 p-8 rounded-lg">
            <div class="text-4xl mb-4">🎯</div>
            <h3 class="text-xl font-bold mb-2">Reach Your Goals</h3>
            <p class="text-gray-600">Set personalized goals and track your progress toward a healthier you.</p>
          </div>
          <div class="bg-rose-50 p-8 rounded-lg">
            <div class="text-4xl mb-4">💡</div>
            <h3 class="text-xl font-bold mb-2">Smart Recommendations</h3>
            <p class="text-gray-600">Get personalized menu recommendations based on your preferences.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 px-4">
      <div class="max-w-6xl mx-auto text-center">
        <p>&copy; 2025 Eatera. Track your meals, love your health.</p>
      </div>
    </footer>
  </main>
</body>
</html>
