async function csrf() {
  try {
    const response = await fetch('/sanctum/csrf-cookie', { 
      credentials: 'include',
      method: 'GET'
    });
    if (!response.ok) {
      console.error('Failed to get CSRF cookie:', response.status);
    }
  } catch (err) {
    console.error('CSRF fetch error:', err);
  }
}

export async function register(data) {
  await csrf();
  // Add a small delay to ensure cookie is set
  await new Promise(resolve => setTimeout(resolve, 100));
  return window.axios.post('/register', data);
}

export async function login(data) {
  await csrf();
  // Add a small delay to ensure cookie is set
  await new Promise(resolve => setTimeout(resolve, 100));
  return window.axios.post('/login', data);
}

export async function logout() {
  // API logout - requires auth token
  return window.axios.post('/logout');
}

export async function fetchUser() {
  return window.axios.get('/user');
}

export default { register, login, logout, fetchUser };
