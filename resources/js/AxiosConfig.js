import axios from "axios";
import store from './store';
axios.defaults.baseURL = store.state.backend_base_url+'api/';
axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token');

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if(error.response.status === 401){
        if(error.response.data && error.response.data.message === 'Token is Expired' || error.response.data.message === 'Invalid Token'){
            console.log('Token Expired')
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            return window.location.href = '/'
        }
    }
    return Promise.reject(error);
});
