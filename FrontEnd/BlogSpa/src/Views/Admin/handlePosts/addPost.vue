<script setup>
import { axiosClient } from '@/Api/axios';
import Header from '@/components/FrontPageComponents/Header.vue';
import Quill from 'quill';
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const categories = ref([]);
const title = ref('');
const slug = ref('');
const content = ref('');
const image = ref(null);
const selectedCategory = ref('');

const $toast = useToast();

onMounted(async () => {
  try {
    const response = await axiosClient.get('api/categories');
    categories.value = response.data.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
});

const handleImageUpload = (event) => {
  image.value = event.target.files[0];
};
 
const handleSubmit = async () => {
  try {
    const formData = new FormData();
    formData.append('category_id', selectedCategory.value);
    formData.append('title', title.value);
    formData.append('slug', slug.value);
    formData.append('content', content.value);
    if (image.value) {
      formData.append('image', image.value);
    }

    const response = await axiosClient.post('api/admin/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    $toast.success('Post added successfully');
  } catch (error) {
    $toast.error('Error adding post');
    console.error('Error adding post:', error);
  }
};
</script>

<template>
    <div class="box">
    <div class="form-container">
      <h2>Create Post</h2>
      <form @submit.prevent="handleSubmit">

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select v-model="selectedCategory" class="form-select" id="category">
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input v-model="title" type="text" class="form-control" id="title">
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input v-model="slug" type="text" class="form-control" id="slug">
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea v-model="content" class="form-control" id="content" rows="6"></textarea>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <input type="file" class="form-control" id="image" @change="handleImageUpload">
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
  text-align: center;
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
