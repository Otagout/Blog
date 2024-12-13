<script setup>
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import router from '@/index';  


const $toast = useToast()
const posts = ref([]); // Initialize as an array

onMounted(async () => {
  try {
    const response = await axiosClient.get('api/posts');
    posts.value = response.data.data;
    console.log(response);
  } catch (error) {
    console.log('problem fetching data', error);
  }
});
const DeletePost = async(id)=>{
  try{
    await axiosClient.delete(`api/admin/posts/${id}`)
    posts.value = posts.value.filter(post => post.id !== id); // Remove the deleted post from the array

    console.log('deleted successfully')

    $toast.success('Post Deleted successfully');
  } catch (error) {
    $toast.error('Error adding post');
    console.error('Error adding post:', error);
  }
}
const clickHandler = (id) => {
  console.log('id:', id);
  router.push({ name: 'edit_post', params: { id } }); // Navigate to the edit post page with the post ID
};


</script>

<template>
  <div>
    <center><h1>All posts</h1></center>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Category</th>
          <th scope="col">Title</th>
          <th scope="col">Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(post, index) in posts" :key="post.id">
          <th scope="row">{{ post.id }}</th>
          <td>{{ post.category_id }}</td>
          <td>{{ post.title }}</td>
          <td>
            <img :src="post.image_url" alt="Post Image" width="100" v-if="post.image_url"/>
            <span v-else>No Image</span>
          </td>
          <td style="display: flex;">
            <button @click="DeletePost(post.id)" type="button" class="btn btn-danger">Delete</button>
            <button @click="clickHandler(post.id)" type="button" class="btn btn-success">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
}
.table th, .table td {
  border: 1px solid #ddd;
  padding: 8px;
}
.table th {
  background-color: #f2f2f2;
}
</style>
