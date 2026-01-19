@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- Login Prompt (shown when not authenticated) -->
  <div id="loginPrompt" class="bg-white p-8 rounded-lg shadow text-center mb-8 hidden">
    <h2 class="text-2xl font-bold mb-4">Belum Login</h2>
    <p class="text-gray-600 mb-6">Silakan login atau daftar untuk melihat profil Anda</p>
    <div class="flex gap-4 justify-center">
      <a href="/login" class="bg-rose-400 text-white px-6 py-2 rounded font-semibold hover:bg-rose-500">Login</a>
      <a href="/register" class="bg-blue-400 text-white px-6 py-2 rounded font-semibold hover:bg-blue-500">Register</a>
    </div>
  </div>

  <!-- Profile Content (shown when authenticated) -->
  <div id="profileContent" class="hidden">
    <h1 class="text-4xl font-bold mb-8">Profil Saya</h1>
    
    <div id="errorMsg" class="hidden bg-red-100 text-red-700 p-4 rounded mb-6"></div>
    <div id="successMsg" class="hidden bg-green-100 text-green-700 p-4 rounded mb-6"></div>

    <!-- Profile Data -->
    <div class="bg-white p-8 rounded-lg shadow mb-8">
      <h2 class="text-2xl font-bold mb-6">Data Profil Saya</h2>
      
      <form id="profileForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
            <input name="name" type="text" disabled class="w-full border rounded px-3 py-2 bg-gray-50" />
          </div>
          
          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input name="email" type="email" disabled class="w-full border rounded px-3 py-2 bg-gray-50" />
          </div>
          
          <!-- Jenis Kelamin -->
          <div>
            <label class="block text-sm font-semibold mb-2">Jenis Kelamin</label>
            <select name="gender" disabled class="w-full border rounded px-3 py-2 bg-gray-50">
              <option value="">Pilih...</option>
              <option value="male">Laki-laki</option>
              <option value="female">Perempuan</option>
            </select>
          </div>
          
          <!-- Tanggal Lahir -->
          <div>
            <label class="block text-sm font-semibold mb-2">Tanggal Lahir</label>
            <input name="date_of_birth" type="date" disabled class="w-full border rounded px-3 py-2 bg-gray-50" />
          </div>
          
          <!-- Berat Badan (kg) -->
          <div>
            <label class="block text-sm font-semibold mb-2">Berat Badan (kg)</label>
            <input name="weight" type="number" step="0.1" class="w-full border rounded px-3 py-2" placeholder="60" />
          </div>
          
          <!-- Tinggi Badan (cm) -->
          <div>
            <label class="block text-sm font-semibold mb-2">Tinggi Badan (cm)</label>
            <input name="height" type="number" step="0.1" class="w-full border rounded px-3 py-2" placeholder="170" />
          </div>
          
          <!-- Diet Goal (full width) -->
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold mb-2">Target Diet</label>
            <select name="diet_goal" class="w-full border rounded px-3 py-2">
              <option value="">Pilih target diet...</option>
              <option value="deficit">Diet (Turun Berat Badan)</option>
              <option value="maintain">Maintain (Stabil)</option>
              <option value="bulking">Bulking (Naik Berat Badan)</option>
            </select>
            <small class="text-gray-500 block mt-1">Pilihan ini akan mempengaruhi target kalori harian Anda</small>
          </div>
        </div>
        
        <!-- Target Kalori (Read-only) -->
        <div class="bg-blue-50 p-4 rounded border border-blue-200">
          <div class="text-sm text-gray-600 mb-1">Target Kalori Harian (Otomatis dihitung)</div>
          <div class="text-3xl font-bold text-blue-600" id="targetCaloriesDisplay">--</div>
          <div class="text-xs text-gray-500 mt-2">Dihitung berdasarkan Harris-Benedict Formula</div>
        </div>
        
        <!-- Buttons -->
        <div class="flex gap-3 pt-4 border-t">
          <button type="submit" class="bg-rose-400 text-white px-6 py-2 rounded font-semibold hover:bg-rose-500">
            💾 Simpan Perubahan
          </button>
          <button type="reset" class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-50">
            Batal
          </button>
        </div>
      </form>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- BMI Card -->
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="text-gray-600 text-sm font-semibold mb-3">Body Mass Index (BMI)</div>
        <div class="text-3xl font-bold text-blue-600" id="bmiDisplay">--</div>
        <div class="text-xs text-gray-600 mt-2" id="bmiStatus">Belum bisa dihitung</div>
      </div>
      
      <!-- Metabolic Age Card -->
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="text-gray-600 text-sm font-semibold mb-3">Usia Metabolik</div>
        <div class="text-3xl font-bold text-green-600" id="metabolicAgeDisplay">--</div>
        <div class="text-xs text-gray-600 mt-2">Berdasarkan Harris-Benedict</div>
      </div>
      
      <!-- Activity Level Card -->
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="text-gray-600 text-sm font-semibold mb-3">Target Diet</div>
        <div class="text-2xl font-bold text-purple-600" id="dietGoalDisplay">--</div>
        <div class="text-xs text-gray-600 mt-2" id="dietGoalDesc">Belum dipilih</div>
      </div>
    </div>


  </div>
</div>

<script>
const errorMsg = document.getElementById('errorMsg');
const successMsg = document.getElementById('successMsg');
const form = document.getElementById('profileForm');
const loginPrompt = document.getElementById('loginPrompt');
const profileContent = document.getElementById('profileContent');

function showError(msg) {
  errorMsg.textContent = msg;
  errorMsg.classList.remove('hidden');
  setTimeout(() => errorMsg.classList.add('hidden'), 5000);
}

function showSuccess(msg) {
  successMsg.textContent = msg;
  successMsg.classList.remove('hidden');
  setTimeout(() => successMsg.classList.add('hidden'), 5000);
}

// Calculate BMI
function calculateBMI(weight, height) {
  if (!weight || !height) return null;
  const bmi = weight / ((height / 100) ** 2);
  return bmi.toFixed(1);
}

function getBMIStatus(bmi) {
  bmi = parseFloat(bmi);
  if (bmi < 18.5) return 'Kurus (Underweight)';
  if (bmi < 25) return 'Normal';
  if (bmi < 30) return 'Gemuk (Overweight)';
  return 'Sangat Gemuk (Obese)';
}

// Calculate Metabolic Age using Harris-Benedict Formula
function calculateMetabolicAge(weight, height, age, gender) {
  if (!weight || !height || !age || !gender) return null;
  
  let bmr;
  weight = parseFloat(weight);
  height = parseFloat(height);
  age = parseInt(age);
  
  // Harris-Benedict Formula
  if (gender === 'male') {
    bmr = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age);
  } else {
    bmr = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
  }
  
  // Reverse formula to get metabolic age from BMR
  let metabolicAge;
  if (gender === 'male') {
    metabolicAge = (88.362 + (13.397 * weight) + (4.799 * height) - bmr) / 5.677;
  } else {
    metabolicAge = (447.593 + (9.247 * weight) + (3.098 * height) - bmr) / 4.330;
  }
  
  return Math.round(Math.max(0, metabolicAge));
}

// Calculate age from date of birth
function calculateAge(dateOfBirth) {
  if (!dateOfBirth) return null;
  const today = new Date();
  const birthDate = new Date(dateOfBirth);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age;
}

// Check authentication
function checkAuthentication() {
  return new Promise((resolve) => {
    function check() {
      if (window.axios) {
        const token = localStorage.getItem('auth_token');
        if (token) {
          // User is authenticated
          profileContent.classList.remove('hidden');
          loginPrompt.classList.add('hidden');
          loadProfile();
          resolve(true);
        } else {
          // User is not authenticated
          loginPrompt.classList.remove('hidden');
          profileContent.classList.add('hidden');
          resolve(false);
        }
      } else {
        setTimeout(check, 100);
      }
    }
    check();
  });
}

// Load profile data
async function loadProfile() {
  try {
    console.log('Loading profile...');
    const res = await window.axios.get('/profile');
    const profile = res.data;
    console.log('Profile data:', profile);
    
    // Fill form
    form.name.value = profile.user?.name || '';
    form.email.value = profile.user?.email || '';
    form.gender.value = profile.gender || '';
    
    // Format date properly for input[type="date"]
    if (profile.date_of_birth) {
      const dateObj = new Date(profile.date_of_birth);
      const formattedDate = dateObj.toISOString().split('T')[0];
      form.date_of_birth.value = formattedDate;
    }
    
    form.weight.value = profile.weight || '';
    form.height.value = profile.height || '';
    form.diet_goal.value = profile.diet_goal || '';
    
    // Display target calories
    document.getElementById('targetCaloriesDisplay').textContent = (profile.target_calories || 0).toLocaleString();
    
    // Calculate and display BMI
    const bmi = calculateBMI(profile.weight, profile.height);
    if (bmi) {
      document.getElementById('bmiDisplay').textContent = bmi;
      document.getElementById('bmiStatus').textContent = getBMIStatus(bmi);
    }
    
    // Calculate and display Metabolic Age
    const age = calculateAge(profile.date_of_birth);
    const metabolicAge = calculateMetabolicAge(profile.weight, profile.height, age, profile.gender);
    if (metabolicAge !== null) {
      document.getElementById('metabolicAgeDisplay').textContent = metabolicAge + ' tahun';
    }
    
    // Display diet goal
    const dietGoalMap = {
      'deficit': '📉 Diet (Turun Berat)',
      'maintain': '➡️ Maintain (Stabil)',
      'bulking': '📈 Bulking (Naik Berat)'
    };
    const dietDesc = {
      'deficit': 'Target: -300 kcal/hari',
      'maintain': 'Target: Sesuai BMR',
      'bulking': 'Target: +300 kcal/hari'
    };
    
    if (profile.diet_goal) {
      document.getElementById('dietGoalDisplay').textContent = dietGoalMap[profile.diet_goal] || profile.diet_goal;
      document.getElementById('dietGoalDesc').textContent = dietDesc[profile.diet_goal] || '';
    }
  } catch (err) {
    console.error('Load profile error:', err);
    if (err.response?.status === 401) {
      // Not authenticated, show login prompt
      profileContent.classList.add('hidden');
      loginPrompt.classList.remove('hidden');
      localStorage.removeItem('auth_token');
    } else {
      showError('Gagal memuat profil');
    }
  }
}

// Update profile on form submit
if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorMsg.classList.add('hidden');
    successMsg.classList.add('hidden');
    
    const data = {
      gender: form.gender.value,
      date_of_birth: form.date_of_birth.value,
      weight: parseFloat(form.weight.value),
      height: parseFloat(form.height.value),
      diet_goal: form.diet_goal.value,
    };
    
    try {
      console.log('Updating profile:', data);
      const res = await window.axios.post('/profile', data);
      console.log('Profile updated:', res.data);
      showSuccess('Profil berhasil diupdate!');
      loadProfile();
    } catch (err) {
      console.error('Update profile error:', err);
      showError(err.response?.data?.message || 'Gagal mengupdate profil');
    }
  });
}

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
@endsection
