// Quick Community API Test
// Copy-paste ini di browser console setelah login ke komunitas page

const API_BASE = '/api/community';

// Test 1: Fetch posts
console.log('Test 1: Fetching posts...');
fetch(`${API_BASE}/posts`, {
  credentials: 'include'
}).then(r => r.json()).then(d => {
  console.log('✅ Posts loaded:', d.length, 'posts');
  console.log(d);
}).catch(e => console.error('❌ Error:', e));

// Test 2: Get current user
console.log('Test 2: Fetching current user...');
fetch(`/api/user`, {
  credentials: 'include'
}).then(r => r.json()).then(d => {
  console.log('✅ Current user:', d.name, '(ID:' + d.id + ')');
}).catch(e => console.error('❌ Error:', e));

// Test 3: Create a test post (uncomment to use)
/*
const testContent = 'Test post dari komunitas! ' + new Date().toLocaleString();
console.log('Test 3: Creating test post...');
fetch(`${API_BASE}/posts`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
  },
  credentials: 'include',
  body: JSON.stringify({ content: testContent })
}).then(r => r.json()).then(d => {
  console.log('✅ Post created:', d);
}).catch(e => console.error('❌ Error:', e));
*/

console.log('Tests started. Check console for results.');
