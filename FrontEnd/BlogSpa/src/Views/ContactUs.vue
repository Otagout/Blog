<script setup>
import Header from '../components/FrontPageComponents/Header.vue';
import Categorie from '../components/FrontPageComponents/Categorie.vue';
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';
 const posts = ref([]);
const latest = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const toast = useToast();             
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

//send email 
const successMessage =ref('')
const name  =ref('')
const email = ref('')
const message = ref('')
const subject = ref('')

const SendMessage= async()=>{
const payload = {
    name :name.value,
    email:email.value,
    message:message.value,
    subject:subject.value
}
try{
      await axiosClient.post('api/send-message',payload)
    toast.open({
        'message':'email sent successfully',
        'type':'success'
    })
}catch(error){
    console.log('error',error)
    toast.open({
        'message':'somthing went wrong',
        'type':'error'
    })
}
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
            <RouterLink to="/contact"><li><span style="color: black;">Contact</span></li></RouterLink>
            <RouterLink v-if="!auth" to="/login"><li><span style="color: black;">Login</span></li></RouterLink>
            <RouterLink v-if="!auth" to="/register"><li><span style="color: black;">Register</span></li></RouterLink>
            <RouterLink v-if="auth" @click="logout" to="/"><li><span style="color: black;">Logout</span></li></RouterLink>
          </ul>
        </div>
         
      </div>
      <div class="contact-form">
        <div class="container">
          <h1>Contact Us</h1>
          <form @submit.prevent="SendMessage"  >
            <label for="name">Name</label>
            <input type="text"  v-model="name" id="name" name="name" required>
  
            <label for="email">Email</label>
            <input type="email"  v-model="email" id="email" name="email" required>
  
            <label for="subject">Subject</label>
            <input type="text"  v-model="subject" id="subject" name="subject" required>
  
            <label for="message">Message</label>
            <textarea id="message"  v-model="message" name="message" rows="5" required></textarea>
  
            <input type="submit" value="Submit">
          </form>
          <p v-if="successMessage">{{ successMessage }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <style scoped>
  .page-container {
    width: 80vw;
    margin: 0 auto;
    padding-top: 30vh;
    padding-top: 50px
  }
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
  
  .contact-form {
    padding-top: 400px;
    width: 70%;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    height: 60vh; 

  }
  
  .contact-form h1 {
    text-align: center;
  }
  
  .contact-form label {
    display: block;
    margin: 20px 0 5px;
  }
  
  .contact-form input[type="text"],
  .contact-form input[type="email"],
  .contact-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .contact-form input[type="submit"] {
    display: block;
    width: 100%;
    padding: 10px;
    background: #333;
    color: #fff;
    border: 0;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .contact-form input[type="submit"]:hover {
    background: #555;
  }
  </style>