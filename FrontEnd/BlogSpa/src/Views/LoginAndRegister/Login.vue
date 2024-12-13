<template>
     <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" name="password" required>
        </div>

        <button type="submit">Login</button>
      </form>
      <a href="/Register" style="text-decoration: none;">don't have an acount ? register</a>
      <div v-if="errorMessage" class="error-message">
        {{ errorMessage }}
      </div>
    </div>
  </div>
 </template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { axiosClient } from '@/Api/axios'; // Make sure this points to your axios instance
``
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const submitForm = async () => {
  try {
    await axiosClient.get('/sanctum/csrf-cookie'); // Fetch CSRF token if using Laravel Sanctum

    const response = await axiosClient.post('/login', {
      email: email.value,
      password: password.value
    });
    const {role} = response.data.user;
    console.log(role);

    if (response.status === 200 && role =='user') {
      router.push('/');
    }else{
      router.push('/Dashboard');
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Login failed. Please try again.';
  }
};
</script>

<style scoped>
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial, sans-serif;
}

.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  width: 100vw;
  background-color: #f0f0f0;
}

.login-box {
  background-color: #fff;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 8px;
  color: #333;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 12px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

button:hover {
  background-color: #45a049;
}

.error-message {
  margin-top: 20px;
  color: red;
}
</style>
