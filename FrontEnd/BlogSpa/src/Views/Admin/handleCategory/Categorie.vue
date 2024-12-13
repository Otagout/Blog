<script setup>
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';
 const data = ref('')
const name = ref('');
const slug = ref('');
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
  
const handleSubmit = async () => {
  try {
 
    const response = await axiosClient.post('api/categories', { name: name.value, slug: slug.value });
    console.log('Category created:', response.data);
    // Optionally, you can reset the form fields here
    name.value = '';
    slug.value = '';
 
    const $toast = useToast();

let instance = $toast.success('category added successfully');
  
   } catch (error) {
    console.error('Error creating category:', error);
   

  }
};
</script>

<template>
  <div class="box">
    <div class="form-container">
      <h2>Create Category</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Category</label>
          <input v-model="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Slug</label>
          <input v-model="slug" type="text" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.box {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f5f5f5;
 }

.form-container {
  background-color: #fff;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px; /* Adjust width as needed */
  text-align: center;
  width: 100vw;

}

h2 {
  margin-bottom: 1.5rem;
  color: #333;
}

.form-label {
  font-weight: bold;
  color: #555;
}

.form-control {
  width: 100%;
  padding: 0.75rem;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

.btn-primary {
  background-color: #007bff;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 5px;
  color: #fff;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #0056b3;
}
</style>
