<script setup>
import { axiosClient } from '@/Api/axios';
import { ref, onMounted } from 'vue';
import {RouterView} from 'vue-router'
import router from '@/index'; // Assuming index.js or index.ts is correctly configured
import Header from '@/components/FrontPageComponents/Header.vue';
 const data = ref(null); // Use null to indicate initial state

onMounted(async () => {
  try {
    await axiosClient.get('/sanctum/csrf-cookie');
    const response = await axiosClient.get('api/user');
    data.value = response.data;

    if (data.value && data.value.role !== 'admin') {
      router.push('/'); // Redirect to home page if user is not admin
    }
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
});
</script>
<template>
  <Header/>
   <div class="container">
    <aside class="sidebar">
      <nav>
        <ul>
          <li @click="() => router.push('/add_post')">+ New Post</li>
          <li @click="() => router.push('/all_posts')">All Posts</li>
           <li @click="() => router.push('/all_comments')">All Comments</li>
          <li @click="() => router.push('/categories')">Categories</li>

        </ul>
      </nav>
    </aside>
    <main class="main-content">
      <Suspense>
      <RouterView />
    </Suspense>
    </main>
  </div>
</template>

<style scoped>
.container {
  margin-top: 350px;
  display: grid;
  grid-template-columns: 250px 1fr ; /* Sidebar width and main content area takes remaining space */
  gap: 20px; /* Gap between columns */
  height: 100vh; /* Full height of viewport */
  width: 100vw;
}

.sidebar {
  background-color: #f0f0f0;
  padding: 20px;
}

.sidebar nav ul {
  list-style-type: none;
  padding: 0;
}

.sidebar nav ul li {
  cursor: pointer;
  padding: 10px;
  margin-bottom: 10px;
  background-color: #ddd;
  border-radius: 5px;
}

.sidebar nav ul li:hover {
  background-color: #ccc;
}

.main-content {
  padding: 20px;
  background-color: #fff;
  overflow-y: auto; /* Scroll content if it exceeds viewport height */
  max-width: 100%;
}
</style>
