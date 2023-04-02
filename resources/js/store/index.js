import { createStore } from 'vuex'
import axios from "axios";
export default createStore({
  state: {
    token:null,
    user:{},
    backend_base_url:'http://localhost:8000/',
    categories:[],
    meeting_rooms:['Conference Room 1 - VGO', 'Conference Room 2 - VGO', 'Conference Room 3 - VGO', 'PE Meeting Room', 'VGO Open Table', 'SCM Open Table', 'B Gemba Open Table', 'D Gemba Open Table', 'Others']
  },
  getters: {

  },
  mutations: {
    setCategory(state, category){
      state.categories = category
    },
    login(state,token,user){
      this.token = token;
      this.user = user;
      localStorage.setItem('token',token);
      localStorage.setItem('user',user);
    },
    logout(state){
      state.token = '';
      state.user = '';
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    },
    initializeStore(state){
      if(localStorage.getItem('token')){
        state.token = localStorage.getItem('token');
      }
      if(localStorage.getItem('user')){
        state.user = JSON.parse(localStorage.getItem('user'));
      }
    },
    setUser(state,user){
      state.user = user;
    }
  },
  actions: {
    fetchCategory({ commit }) {
      axios.get('/get_category').then(response => {
        commit('setCategory', response.data);
      });
    }
  },
  modules: {

  }
})
