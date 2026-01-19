# 🚀 Login & Registration Test Guide

## Setup Steps (Run These First!)

### 1. **Database & Migrations**
```bash
# Clear old data (optional)
php artisan migrate:refresh

# Or just run migrations
php artisan migrate
```

### 2. **Create Admin User via Tinker**
```bash
php artisan tinker
```

Then paste this:
```php
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ajt.com',
    'password' => bcrypt('admin2025'),
    'role' => 'admin'
]);

App\Models\UserProfile::create([
    'user_id' => $user->id,
    'gender' => 'male',
    'date_of_birth' => '2000-01-01',
    'weight' => 70,
    'height' => 170,
    'diet_goal' => 'maintain',
    'target_calories' => 2000,
]);

exit
```

### 3. **Start Servers**

**Terminal 1 (Vite - HMR & CSS)**
```bash
npm run dev
```

**Terminal 2 (Laravel)**
```bash
php artisan serve
```

## ✅ Test Steps

### **Test 1: Landing Page**
- Open: http://localhost:8000
- Should see: "Track Your Meals, Love Your Health"
- Click "Register" or "Login" buttons

### **Test 2: Register as Calomate**
1. Click **Register** button
2. Fill form:
   - Name: `Test User`
   - Email: `user@test.com`
   - Password: `password123`
   - Confirm: `password123`
3. Click **Register**
4. Should auto-login and redirect to **Dashboard** (/dashboard)
5. See navbar with: Home, Dashboard, Food Journal, Rekomendasi, Profile dropdown

### **Test 3: Login as Calomate** 
1. Logout first (click Profile → Logout)
2. Go to: http://localhost:8000/login
3. Fill:
   - Email: `user@test.com`
   - Password: `password123`
4. Click **Login**
5. Should redirect to **Dashboard** (/dashboard)
6. Navbar shows: Home, Dashboard, Food Journal, Rekomendasi, Profile

### **Test 4: Login as Admin**
1. Go to: http://localhost:8000/login
2. Fill:
   - Email: `admin@ajt.com`
   - Password: `admin2025`
3. Click **Login**
4. Should redirect to **/admin/dashboard**
5. Navbar is DIFFERENT:
   - Dark background (gray-900 to gray-800)
   - Shows: Dashboard, Kelola Makanan, Komunitas, Artikel
   - Red Logout button

### **Test 5: Admin CRUD Makanan**
1. Logged in as admin at /admin/dashboard
2. Click **Kelola Makanan**
3. Should see table (might be empty)
4. Click **Tambah Makanan**
5. Fill:
   - Nama: `Nasi Goreng`
   - Kalori: `350`
   - Protein: `10`
   - Karbohidrat: `50`
   - Lemak: `12`
6. Click **Simpan**
7. Should redirect back to list and see new item
8. Test Edit & Delete

### **Test 6: Calomate Food Journal**
1. Logout admin, login as calomate user
2. Click **Food Journal**
3. Should see form to add entry
4. Try adding a food entry

### **Test 7: Logout Both Roles**
1. Click Profile dropdown (for calomate) or red button (for admin)
2. Click **Logout**
3. Should redirect to home page
4. Navbar should show Login/Register buttons

## 🐛 Debugging

### If Login Fails:
1. Open browser DevTools (F12)
2. Go to **Network** tab
3. Try login again
4. Look for red errors (failed requests)
5. Check **Console** tab for JS errors
6. Common issues:
   - `CSRF token mismatch` → Run `php artisan migrate`
   - `Invalid credentials` → Check email/password in database
   - `Connection refused` → Make sure both `npm run dev` and `php artisan serve` are running

### Check Database:
```bash
php artisan tinker

# List all users
App\Models\User::all();

# Check if admin exists
App\Models\User::where('email', 'admin@ajt.com')->first();
```

## 🎯 Expected Behavior

| Action | Expected Result |
|--------|-----------------|
| Register → Success | Redirects to `/dashboard` |
| Login as Calomate | Redirects to `/dashboard`, light navbar |
| Login as Admin | Redirects to `/admin/dashboard`, dark navbar |
| Logout Calomate | Redirects to home (/) |
| Logout Admin | Redirects to home (/) |
| Add Food (Admin) | Shows in table, can edit/delete |
| Food Journal (Calomate) | Can add entries via form |

---

**Run these tests and let me know which step fails!** 🎉
