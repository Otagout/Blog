<script setup>
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';
const comments =ref('')
  const DeleteComment=async(id)=>{
    try{
  
await axiosClient.delete(`api/deleteComment/${id}`)
await axiosClient.get('api/comments');
    }catch(e){
      console.log('error deleting',e)
    }
  }
onMounted(async () => {
  try {
    const response = await axiosClient.get('api/comments');
    comments.value = response.data.data
    console.log('comments',comments.value)
   } catch (err) {
    console.error('Error fetching categories:', err);
   }
});
</script>
<template>
    <h1>all comments</h1>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">User</th>
      <th scope="col">Comment</th>
      <th scope="col">action</th>
    </tr>
  </thead>
  <tbody>
     <tr v-for="item in comments" :key="item.id">
      <th>{{ item.id }}</th>
      <th>{{item.author_name}}</th>
      <th>{{item.content}}</th>
       <th> <button  class="btn btn-danger" @click="DeleteComment(item.id)">Delete</button></th>
     </tr>
  </tbody>
 </table>
  </template>
  
  <style scoped>
   
  </style>