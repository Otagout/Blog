<script setup>
import Header from '../components/FrontPageComponents/Header.vue';
import Categorie from '../components/FrontPageComponents/Categorie.vue';
import { axiosClient } from '@/Api/axios';
import { onMounted, ref, watch } from 'vue';
import router from '..';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toast-notification';

const posts = ref([]);
const latest = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const route = useRoute();
const postId = ref(parseInt(route.params.id));
const post = ref(null);
const auth = ref(false);
const data = ref(null);

const toast = useToast()

const comments = ref([]);
const relatedComments = ref([]); // Ref to hold filtered comments
const content = ref('');
const createdAt = ref(new Date().toISOString());

const fetchComment = async () => {
  try {
    const response = await axiosClient.get('api/comments');
    comments.value = response.data.data;
    console.log('post id ', postId.value);
    relatedComments.value = comments.value.filter(c => c.post_id === postId.value);
    if (relatedComments.value.length > 0) {
      console.log('comments', relatedComments.value);
    }
  } catch (error) {
    console.log('Error fetching comments', error);
  }
};

const fetchPost = async () => {
  try {
    const response = await axiosClient.get('api/posts');
    const allPosts = response.data.data;
    post.value = allPosts.find(p => p.id === postId.value);
    if (post.value) {
      console.log('Post:', post.value.title);
    }
  } catch (error) {
    console.error('Error fetching post:', error);
  }
};

const fetchUserData = async () => {
  try {
    const response = await axiosClient.get('api/user');
    data.value = response.data;
    auth.value = !!data.value;
    if (auth.value) {
      console.log('User:', data.value.name);
    }
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
};

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

const addComment = async (event) => {
  event.preventDefault();

  if (!auth.value) {
    await fetchUserData();
    if (!auth.value) {
      console.log('You need to log in to comment.');
      toast.error('please login to comment',{
   timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: true,
  closeButton: "button",
  icon: true,
  rtl: false
    })
      return;
    }
  }

  try {
    const commentData = {
      post_id: postId.value,
      content: content.value,
      author_name: data.value ? data.value.name : '',
      author_email: data.value ? data.value.email : '',
      created_at: new Date().toISOString()
    };

    await axiosClient.post('api/comments/store', commentData);
    
    fetchComment();
    content.value = '';
  } catch (error) {
    console.log('Error adding comment', error);

  }
};

onMounted(() => {
  fetchPost();
  fetchUserData();
  fetchComment();
});
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
          <li v-if="auth" @click="logout"><span style="color: black;">Logout</span></li>
        </ul>
      </div>
      <div class="middle">
        <h1>Devy</h1>
        <h2>All dev news in one place </h2>
      </div>
    </div>
    <Categorie />
    <div class="content" style="padding-top: 200px;">
      <div class="card mb-3" style="width: 60vw; align-items: center;justify-content: center;">
        <img class="card-img-top"   v-if="post" style="width: 90%;"  :src="post.image_url"    alt="..." />
        <p v-else>image not found </p>

        <div class="card-body">
          <h5 class="card-title" v-if="post">{{ post.title }}</h5>
          <p v-else>Loading post...</p>
          <p class="card-text" v-if="post">{{ post.content }}.</p>
          <p v-else>Loading post...</p>
          <p class="card-text"><small class="text-muted" v-if="post">Last updated 3 mins ago</small></p>
        </div>
      </div>
    </div>
    <div></div>
    <div class="comment-section">
      <h2>Comments</h2>
      <form class="comment-form" @submit="addComment">
        <textarea v-model="content" id="comment-input" placeholder="Write your comment here..."></textarea>
        <button id="submit-comment" type="submit">Submit</button>
      </form>
      <div class="comments-list">
        <div v-for="comment in relatedComments" :key="comment.id" class="comment">
          <p><strong>{{ comment.author_name }}</strong> {{ comment.content }}</p>
        </div>
      </div>
    </div>
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
  justify-content: center;
  align-items: center;
  padding-left: 200px;
  
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

/* Improved Comment Section */
.comment-section {
  width: 100%;
  margin-top: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
}

.comment-section h2 {
  margin-bottom: 20px;
  font-size: 24px;
}

.comment-form {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}
.blog-post{
  width: 400px;
}

#comment-input {
  width: 100%;
  padding: 15px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

#submit-comment {
  align-self: flex-end;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

#submit-comment:hover {
  background-color: #0056b3;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.comment {
  background-color: #fff;
  padding: 10px 15px;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comment p {
  margin: 0;
}

.comment strong {
  display: block;
  margin-bottom: 5px;
}
</style>
