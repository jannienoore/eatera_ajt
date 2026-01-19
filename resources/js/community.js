// Community API helpers
const API_BASE = '/api';

export async function fetchPosts() {
  try {
    const res = await window.axios.get(`${API_BASE}/community/posts`);
    return res.data;
  } catch (err) {
    console.error('Error fetching posts:', err);
    throw err;
  }
}

export async function createPost(content) {
  try {
    const res = await window.axios.post(`${API_BASE}/community/posts`, { content });
    return res.data;
  } catch (err) {
    console.error('Error creating post:', err);
    throw err;
  }
}

export async function updatePost(postId, content) {
  try {
    const res = await window.axios.put(`${API_BASE}/community/posts/${postId}`, { content });
    return res.data;
  } catch (err) {
    console.error('Error updating post:', err);
    throw err;
  }
}

export async function deletePost(postId) {
  try {
    const res = await window.axios.delete(`${API_BASE}/community/posts/${postId}`);
    return res.data;
  } catch (err) {
    console.error('Error deleting post:', err);
    throw err;
  }
}

export async function toggleLike(postId) {
  try {
    const res = await window.axios.post(`${API_BASE}/community/posts/${postId}/like`);
    return res.data;
  } catch (err) {
    console.error('Error toggling like:', err);
    throw err;
  }
}

export async function addComment(postId, content) {
  try {
    const res = await window.axios.post(`${API_BASE}/community/posts/${postId}/comment`, { content });
    return res.data;
  } catch (err) {
    console.error('Error adding comment:', err);
    throw err;
  }
}

export async function reportPost(postId, reason) {
  try {
    const res = await window.axios.post(`${API_BASE}/community/posts/${postId}/report`, { reason });
    return res.data;
  } catch (err) {
    console.error('Error reporting post:', err);
    throw err;
  }
}

export default {
  fetchPosts,
  createPost,
  updatePost,
  deletePost,
  toggleLike,
  addComment,
  reportPost,
};
