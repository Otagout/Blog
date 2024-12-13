<script setup>
import { axiosClient } from '@/Api/axios';
import router from '@/index';
import { ref } from 'vue';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const errors = ref('');

const submitRegister = async () => {
  try {
    await axiosClient.get('/sanctum/csrf-cookie');

    if (password.value !== passwordConfirmation.value) {
      throw new Error('Passwords do not match');
    }

    const formData = {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    };

    const response = await axiosClient.post('register', formData);
    console.log('Registration successful:', response.data);
    router.push('/');

  } catch (error) {
    console.error('Error registering:', error);
    errors.value = error.response.data
  }
};
</script>

<template>
  <div class="mainForm">
    <form @submit.prevent="submitRegister">

      <h2>Register</h2>
       
 
      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input v-model="name" type="text" id="username" placeholder="Enter your username" required>
        </div>
      <div class="form-group">
        <label for="email" class="form-label">Email address</label>
        <input v-model="email" type="email" id="email" placeholder="name@example.com" required>
        {{ errors.email }}
      </div>
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input v-model="password" type="password" id="password" placeholder="Enter your password" required>
      </div>
      <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input v-model="passwordConfirmation" type="password" id="password_confirmation" placeholder="Confirm your password" required>
      </div>
       <button type="submit" class="btn">Submit</button>
       <a href="/Login" style="text-decoration: none;"> already have an acount ? login</a>

    </form>
  </div>
</template>

<style scoped>
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.mainForm {
    display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  width: 100vw;
  background-color: #f0f0f0;
}
.mainForm form {
    
  background-color: #ffffff;
  width: 500px;
  border-radius: 10px;
  padding: 40px;
}
#app {
  display: none; /* This hides the #app element */
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

.btn {
  width: 100%;
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

.btn:hover {
  background-color: #45a049;
}
</style>
