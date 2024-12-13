
<script setup>
import { axiosClient } from '@/Api/axios';
import { onMounted, ref } from 'vue';

const posts = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

const fetchPosts = async (page = 1) => {
  try {
    const response = await axiosClient.get(`api/posts?page=${page}`);
    posts.value = response.data.data;
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
</script>
 

<style scoped>
.blog-grid {
  width: 100vw;
  display: grid;
  grid-template-columns: 3fr 1fr; /* Main content takes 3 parts, sidebar takes 1 part */
  gap: 20px; /* Space between main content and sidebar */
  padding: 200px; /* Padding around the grid */
 }

.content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid for blog posts */
  gap: 20px; /* Space between blog posts */
}

.blog-post {
  background-color: #f0f0f0;
  padding: 20px;
  border-radius: 8px;
  

}

.card-img-top {
  border-radius: 8px;
}

.sidebar {
  background-color: #e0e0e0;
  padding: 20px;
  border-radius: 8px;
}

.widget {
  margin-bottom: 20px;
}

.widget h3 {
  margin-top: 0;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination button {
  padding: 10px 20px;
  margin: 0 5px;
}

.pagination span {
  margin: 0 10px;
}
.card-body{
 
}
</style>
