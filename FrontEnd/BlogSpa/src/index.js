import { createWebHistory ,createRouter } from "vue-router";
import FrontPage from '../src/Views/FrontPage.vue'
import Login from '../src/Views/LoginAndRegister/Login.vue'
import Register from '../src/Views/LoginAndRegister/Register.vue'
import Admin from '../src/Views/Admin/AdminDaschboard.vue'
import AddPost from '../src/Views/Admin/handlePosts/addPost.vue'
import AllPost from '../src/Views/Admin/handlePosts/AllPosts.vue'
import EditPost from '../src/Views/Admin/handlePosts/EditPost.vue'
import byCategory from '../src/Views/byCategorie.vue'
import Comments from '../src/Views/Admin/handleComments/Comments.vue'
import Category from '../src/Views/Admin/handleCategory/Categorie.vue'
import ContactUs from '../src/Views/ContactUs.vue'
import Content from "./Views/Content.vue";
export const ADMINDASHBOARD = '/Dashboard'

const router = createRouter({
    history:createWebHistory(import.meta.env.BASE_URL),
    routes:[
        {
     
        path:'/',
        name:'frontPage',
        component:FrontPage
   
        },
      
         {
            path:'/Login',
            name:'Login',
            component:Login
         },
         {
            path: '/Register',
            name:'Register',
            component:Register

         },
         {
            path: '/Content/:id',  
            name: 'Content',       
            component: Content 

         },

         {
            path: '/category/:id',  
            name: 'category',       
            component: byCategory 

         },
         
         {
            path: '/ContactUs',  
            name: 'ContactUs',       
            component: ContactUs 

         },
         {
            path: ADMINDASHBOARD,
            name:'admin',
            component:Admin,
             children:[
               {
                  path:'/add_post',
                  name:'addPost',
                  component:AddPost
      
               },
               {
                  path:'/all_posts',
                  name:'AllPost',
                  component:AllPost
      
               },  
               
               {
                  path:'/all_comments',
                  name:'AllComments',
                  component:Comments
      
               },  
               {
                  path:'/categories',
                  name:'Categories',
                  component:Category
               },  
               {
                  path:'/edit_post/:id',
                  name:'edit_post',
                  component:EditPost
               },  

               
             ]
     
         
         
      },

]
})
export const redirectToDashboard = (roleType)=>{
   switch(roleType){
      case 'admin':
         return ADMINDASHBOARD
      case 'user':
         return '/'
   }
}

export default router