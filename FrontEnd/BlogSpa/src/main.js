import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from '.'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
 
//toast
import Toast from "vue-toastification";
import 'vue-toastification/dist/index.css';

const app = createApp(App) 
app.use(router)
app.use(ToastPlugin);
app.use(Toast)
 
app.mount('#app')
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';


// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
 