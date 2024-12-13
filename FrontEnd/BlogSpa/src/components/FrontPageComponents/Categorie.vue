<script setup>
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';

const categories = ref([]);
const error = ref(null);

onMounted(async () => {
  try {
    const response = await axiosClient.get('api/categories');
    categories.value = response.data.data;
  } catch (err) {
    console.error('Error fetching categories:', err);
    error.value = 'Error fetching categories';
  }
});
</script>
<template>

 
  <div class="categories" v-for="category in categories" :key="category.id">
    <RouterLink to="/"><button>All</button></RouterLink>
    <RouterLink :to="`/category/${category.id}`"  v-for="category in categories" :key="category.id">
     <button>{{ category.name }}</button>  
  </RouterLink>
     
     
  </div>
  
</template>

<style scoped>
.categories {
  display: flex;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
  position: fixed;
  top: 10vh; /* Position below the header */
  left: 50%; /* Center horizontally */
  transform: translateX(-50%); /* Center horizontally */
  width: 100%; /* Full width */
  background-color: #fff; /* Example background color */
  padding: 10px; /* Adjust padding as needed */
  z-index: 1000; /* Ensure it stays on top */
}

.categories button {
padding: 10px 20px;    
margin: 0 5px;  
border: none;
background-color: #f0f0f0;  
cursor: pointer;
border-radius: 20px;
}
</style>