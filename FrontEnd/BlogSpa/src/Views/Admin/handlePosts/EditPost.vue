<script setup>
import { axiosClient } from '@/Api/axios';
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toast-notification';
import { useRoute } from 'vue-router';
import router from '@/index';

const route = useRoute();
const selectedCategory = ref('');
const title = ref('');
const slug = ref('');
const content = ref('');
const image = ref(null);
const categories = ref([]);
const errors = ref({});
const $toast = useToast();

onMounted(async () => {
  try {
    await axiosClient.get('/sanctum/csrf-cookie');
    const response = await axiosClient.get('api/user');
    const userData = response.data;

    // Redirect to home page if user is not admin
    if (!userData || userData.role !== 'admin') {
      router.push('/');
    }
  } catch (error) {
    console.error('Error fetching user data:', error);
  }

  try {
    const postId = parseInt(route.params.id);
    const [postResponse, categoriesResponse] = await Promise.all([
      axiosClient.get(`api/posts`),
      axiosClient.get('api/categories')
    ]);

    const postData = postResponse.data.data.find(post => post.id === postId);
    console.log('Post data:', postData);
 
    // Update reactive variables with fetched data
    selectedCategory.value = postData.category_id;
    title.value = postData.title;
    slug.value = postData.slug;
    content.value = postData.content;
    // Assuming image_url is part of post data, handle image separately if needed
    // image.value = postData.image_url;

    categories.value = categoriesResponse.data.data;
    console.log('Categories:', categories.value);
  } catch (error) {
    console.error('Error fetching post or categories:', error);
    $toast.error('Error fetching post or categories');
  }
});

const handleImageUpload = (event) => {
  image.value = event.target.files[0];
};

const handleEdit = async () => {
  try {
    errors.value = {}; // Reset errors

    const postId = parseInt(route.params.id);
    const postData = {
      category_id: selectedCategory.value,
      title: title.value,
      slug: slug.value,
      content: content.value,
      
      image: image.value, // Assuming you handle file upload separately if needed
    };

    const response = await axiosClient.put(`api/admin/posts/${postId}`, postData);
    console.log('PUT response:', response.data);
    $toast.success('Post Edited successfully');
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
      for (const key in errors.value) {
        $toast.error(errors.value[key][0]);
      }
    } else {
      $toast.error('Error editing post');
      console.error('Error editing post:', error);
    }
  }
};
</script>
<template>
  <div class="box">
    <div class="form-container">
      <center><h2>Edit Post</h2></center>
      <form @submit.prevent="handleEdit">

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select v-model="selectedCategory" class="form-select" id="category">
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
          <span v-if="errors.category_id" class="text-danger">{{ errors.category_id[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input v-model="title" type="text" class="form-control" id="title">
          <span v-if="errors.title" class="text-danger">{{ errors.title[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input v-model="slug" type="text" class="form-control" id="slug">
          <span v-if="errors.slug" class="text-danger">{{ errors.slug[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea v-model="content" class="form-control" id="content" rows="6"></textarea>
          <span v-if="errors.content" class="text-danger">{{ errors.content[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <input type="file" class="form-control" id="image" @change="handleImageUpload">
          <span v-if="errors.image" class="text-danger">{{ errors.image[0] }}</span>
        </div>

        <button type="submit" class="btn btn-success">Edit</button>
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
