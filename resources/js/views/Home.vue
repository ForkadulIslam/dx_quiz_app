
<template>
    <div class="container">
        <div class="multisteps_form overflow-hidden position-relative" id="wizard" style="background-image: url('images/bg_1.png')">
            <!------------------------- Step-1 ----------------------------->
            <div class="multisteps_form_panel" v-if="!is_logged_in" :style="{display:!is_logged_in ? 'block':'none'}">
                <div class="question_title">
                    <h1 class="text-center py-5 animate__animated animate__fadeInRight animate_25ms">Give us your name before starting your quiz</h1>
                </div>
                <div class="row pt-3">
                    <div class="col-xs-9 col-xs-3-offset">
                        <div class="form-container">
                            <form action="#" @submit.prevent="handleSubmit">
                                <div v-if="user_name_input.form_error" class="form-group alert alert-danger">
                                    <p v-text="user_name_input.form_error.user_name"></p>
                                </div>
                                <div class="input-group">
                                    <input v-model="user_name_input.user_name" style="height: 67px;margin-bottom: 25px" type="text" class="form-control form-control-lg" placeholder="User name...">
                                </div>
                                <div class="input-group">
                                    <button type="submit" class="f_btn" style="width: 100%">
                                        CREATE SESSION
                                        <span v-if="is_loading">
                                            <i class="fas fa-spinner fa-fw fa-xl margin-right-md fa-spin"></i>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="multisteps_form_panel" v-if="user" :style="{display:user ? 'block':'none'}">
                <div class="question_title">
                    <h1 class="text-center py-5 animate__animated animate__fadeInRight animate_25ms">Hi {{ user.user_name }}, Here is your Quiz Stats</h1>
                    <a href="#" @click.prevent="logout" class="btn btn-danger btn-xs animate__animated animate__fadeInLeft animate_25ms">LOGOUT</a>
                </div>
                <div class="row pt-3">
                    <ul class="text-center">
                        <li class="step_1 position-relative d-inline-block animate__animated animate__fadeInRight animate_100ms">
                            <label>Correct : {{ user.stats.php.score  }}</label>
                            <span class="position-absolute"> PHP</span>
                            <span class="">SKIP : {{ user.stats.php.skip  }}</span>
                        </li>
                        <li class="step_1 position-relative d-inline-block animate__animated animate__fadeInRight animate_100ms">
                            <label>Correct : {{ user.stats.ajax.score  }}</label>
                            <span class="position-absolute">AJAX</span>
                            <span class="">SKIP : {{ user.stats.ajax.skip  }}</span>
                        </li>
                    </ul>
                    <ul class="text-center">
                        <li class="step_1 position-relative d-inline-block animate__animated animate__fadeInRight animate_100ms">
                            <label>Correct : {{ user.stats.jquery.score  }}</label>
                            <span class="position-absolute">JQUERY</span>
                            <span class="">SKIP : {{ user.stats.jquery.skip  }}</span>
                        </li>
                        <li class="step_1 position-relative d-inline-block animate__animated animate__fadeInRight animate_100ms">
                            <label>Correct : {{ user.stats.html.length  }}</label>
                            <span class="position-absolute">HTML</span>
                            <span class="">SKIP : {{ user.stats.html.skip  }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Custom-js include -->

<style scoped>
    .row li:after{
        content: none;
    }
    .row li label{
        padding-left: 8.375rem;
    }
</style>

<script>
    import axios from 'axios';
    export default {
        name: 'Home',
        data(){
            return {
                is_loading:true,
                is_logged_in:false,
                user:null,
                user_name_input:{
                    form_error:null,
                    user_name:'',
                },
            }
        },
        mounted() {

            this.fetchAuth();
            //console.log(this.$store.dispatch('fetchCategory'))

        },
        methods: {

            async handleSubmit(){
                try {
                    this.is_loading = true;
                    let form_data =this.user_name_input;
                    this.user_name_input = {
                        form_error:null,
                        user_name:'',
                    };
                    const response = await axios.post('login',form_data);
                    this.is_loading = false;
                    this.user = response.data.user;
                    this.is_logged_in = true;
                    console.log(response.data);
                    localStorage.setItem('token',response.data.authorization.token);
                    localStorage.setItem('user',JSON.stringify(response.data.user));
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

                } catch (error) {
                    this.is_loading = false
                    this.user_name_input.form_error = error.response.data;
                }
            },
            async fetchAuth(){
                try {
                    this.is_loading = true;
                    this.is_logged_in = true;
                    const response = await axios.get('fetch_auth_info');
                    this.user = response.data;
                    this.is_loading = false;
                } catch (error) {
                    this.is_logged_in = false;
                    this.is_loading = false;
                }
            },

            logout(){
                axios.get('logout').then(function(res){
                    this.$store.commit('logout');
                    window.location.href = '/'
                }).catch(err=>{
                    this.$store.commit('logout');
                    console.log(err);
                    window.location.href = '/';
                })
            }

        }
    }
</script>
