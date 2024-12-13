<script setup>
import { ref, onMounted } from 'vue';
import { axiosClient } from '@/Api/axios';
import router from '@/index';

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
</script>

<template>
  <div class="head">
    <div class="head-content">
      <div class="logged" v-if="auth">{{ data.name }}</div>
      <ul>
        <RouterLink to="/"><li><span style="color: black;">Home</span></li></RouterLink> 
        <RouterLink to="/about"><li><span style="color: black;">About</span></li></RouterLink> 
        <RouterLink to="/Contact"><li><span style="color: black;">Contact</span></li></RouterLink> 
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
</template>

<style scoped>
.logged {
  right: 1%;
  top: 1%;
  position: absolute;
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
.head-content ul li {
  list-style-type: none;
  text-decoration: none;
  list-style: none;
}
.head-content ul {
  display: flex;
  justify-content: center;
  padding: 0;
  margin: 0;
  list-style-type: none;
}
.head-content li {
  padding: 0 20px;
  cursor: pointer;
}
.middle {
  text-align: center;
}
.middle h1 {
  margin: 10px 0 0;
}
.middle h2 {
  margin: 0;
}
</style>
