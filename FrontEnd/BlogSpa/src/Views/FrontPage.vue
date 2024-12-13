<script setup>
import Header from '../components/FrontPageComponents/Header.vue';
import Categorie from '../components/FrontPageComponents/Categorie.vue';
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';
 const posts = ref([]);
const latest = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
 const fetchPosts = async (page = 1) => {
  try {
    const response = await axiosClient.get(`api/posts?page=${page}`);
    posts.value = response.data.data;
    const res = await axiosClient.get('api/posts')
    latest.value = res.data.data.slice(0,3)
    console.log(latest)
    currentPage.value = response.data.meta.current_page;
    totalPages.value = response.data.meta.last_page;
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    fetchPosts(currentPage.value - 1);
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    fetchPosts(currentPage.value + 1);
  }
};

onMounted(() => {
  fetchPosts();
});


const auth = ref(false);
const data = ref(null);

onMounted(async () => {
  try {
    const response = await axiosClient.get('api/user');
    data.value = response.data;
    if (data.value) {
      console.log('log credentials:', data.value.name);
      auth.value = true;
    }else{
      auth.value =false
    }
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
});

const logout = async () => {
  try {
    await axiosClient.post('api/logout');
    console.log('Logout successful');
    auth.value = false;
    router.push('/');
  } catch (error) {
    console.error('Error logging out:', error);
  }
};

const getPhoto=()=>{
  return '/public/images/'
}
</script>

<template>
  <div class="page-container">
    <div class="head">
    <div class="head-content">
      <div class="logged" v-if="auth">{{ data.name }}</div>
      <ul>
        <RouterLink to="/"><li><span style="color: black;">Home</span></li></RouterLink> 
        <RouterLink to="/about"><li><span style="color: black;">About</span></li></RouterLink> 
        <RouterLink to="/ContactUs"><li><span style="color: black;">Contact</span></li></RouterLink> 
        <RouterLink v-if="!auth" to="/Login"><li><span style="color: black;">Login</span></li></RouterLink> 
        <RouterLink v-if="!auth" to="/Register"><li><span style="color: black;">Register</span></li></RouterLink> 
        <RouterLink v-if="auth" @click="logout" to="/"><li><span style="color: black;">Logout</span></li></RouterLink>
      </ul>
    </div>
    <div class="middle">
      <h1>Devy</h1>
      <h2>All dev news in one place</h2>
    </div>
  </div>
    <Categorie />
    <main>
      <div class="blog-grid">
        <main class="content">
          <!-- Blog posts -->
          <RouterLink v-for="post in posts" :key="post.id" :to="`/Content/${post.id}`" style="list-style: none; text-decoration: none;" class="blog-post">
            <div class="card">
              <img
              :src='post.image_url'
                class="card-img-top"
                alt="Post Image"
              />
              <div class="card-body">
                <p>published at : {{ post.published_at }}</p> 
                <h5 class="card-title">{{ post.title }}</h5>
                <p class="card-text">{{ post.content.slice(0, 50) }}</p>
               </div>
            </div>
          </RouterLink>
        </main>
         <aside class="sidebar">
          <!-- Sidebar content -->
          <div class="widget">
            <h3>Latest Posts</h3>
            <ul v-for="index in latest" :key="index.id">
              <div class="sidebar-content" style="display: flex; gap: 10px;">
                <img :src="index.image_url"  style="width: 100px; border-radius: 10px;" alt="Latest Post Image">
                <li> <h6>{{ index.title }}</h6></li>
              </div>
            </ul>
          </div>
        </aside>
      </div>
       <!-- Pagination -->
      <div class="pagination">
        <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Positioning and layout for logged in user indicator */
.logged {
  right: 1%;
  top: 1%;
  position: absolute;
}

/* Header styling */
.head {
  width: 100%;
  height: 16vh;
  background-color: rgb(107, 165, 212);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 10px;
}

.head-content ul {
  display: flex;
  justify-content: center;
  padding: 0;
  margin: 0;
  list-style-type: none;
}

.head-content ul li {
  list-style: none;
  padding: 0 20px;
  cursor: pointer;
}

.middle {
  text-align: center;
}

.middle h1,
.middle h2 {
  margin: 10px 0 0;
}

.page-container {
  max-width: 80vw;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0 20px;
}

/* Blog grid layout */
.blog-grid {
  width: 80vw;
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 20px;
  padding: 20px;
  padding-top: 40vh; /* Adjusted padding to account for the header */
}

.content {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.blog-post {
  background-color: #fdfcfc;
  padding: 20px;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
}

/* Remove border from RouterLink */
.blog-post a {
  text-decoration: none; /* Remove underline */
  color: inherit; /* Inherit text color */
  border: none; /* Remove any border */
  outline: none; /* Remove outline */
}

.card-img-top {
  width: 100%;
  height: auto;
  border-radius: 8px;
}

.sidebar {
  background-color: #fdfcfc;
  padding: 20px;
  border-radius: 8px;
}

.widget {
  margin-bottom: 20px;
}

.widget h3 {
  margin-top: 0;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination button {
  padding: 10px 20px;
  margin: 0 5px;
}

.pagination span {
  margin: 0 10px;
}

.card-body {
  padding: 10px;
  align-items: center;
  text-align: center;
}

/* Responsive Styles */
@media (max-width: 1008px) {
  .head-content ul {
    flex-direction: column;
  }

  .head-content ul li {
    padding: 10px 0;
  }

  .blog-grid {
    grid-template-columns: 1fr;
    width: 90vw;
  }

  .content {
    grid-template-columns: 1fr;
  }

  .sidebar-content {
    flex-direction: column;
    align-items: center;
  }

  .sidebar-content img {
    width: 80px;
  }

  .card-body p,
  .card-body h5,
  .card-body .card-text {
    text-align: center;
  }
}

@media (max-width: 480px) {
  .head-content ul li {
    padding: 5px 0;
  }

  .sidebar-content {
    flex-direction: column;
    align-items: center;
  }

  .sidebar-content img {
    width: 60px;
  }
}
</style>
