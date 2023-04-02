import axios from 'axios'
import VueAxios from 'vue-axios'
import { createApp } from 'vue'
import App from './views/App.vue'
import router from './router'
import store from './store'
import middleware from "@grafikri/vue-middleware"
import './AxiosConfig';
import 'vue-universal-modal/dist/index.css';


router.beforeEach(middleware({store}));
const app = createApp(App);
app.use(router).use(store).use(middleware).use(VueAxios, axios);
app.mount('#app');
