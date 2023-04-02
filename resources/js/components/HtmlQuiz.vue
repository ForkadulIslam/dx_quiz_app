<template>
    <div class="container">
        <div class="multisteps_form overflow-hidden position-relative" id="wizard" style="background-image: url('images/bg_1.png')">
            <div v-show="currentTab < 4">
                <!------------------------- Step-1 ----------------------------->
                <div v-for="(question, index) in quiz" class="multisteps_form_panel" :key="index">
                    <div class="question_title">
                        <h1 class="text-center py-5 animate__animated animate__fadeInRight animate_25ms" v-text="question.question"></h1>
                    </div>

                    <div :class="'row ' + (chunk_sl == 0 ? 'pt-5':'')"  v-for="(chunked_options,chunk_sl) in question.options" :key="chunk_sl">
                        <ul class="text-center">
                            <li ref="option_list_item" @click="select_answer(option_sl, question.id, option.id)" :key="option_sl" v-for="(option, option_sl) in chunked_options" class="option_li step_1 position-relative d-inline-block animate__animated animate__fadeInRight animate_150ms">
                                <label v-text="option.option"></label>
                                <span class="position-absolute" v-text="(Number(option_sl)+1)"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <!-- Progress bar -->
                        <div class="step_progress position-absolute text-center step">
                            <span class="text-capitalize">question {{index+1}} / {{ quiz.length }}</span>
                            <div class="progress rounded-pill">
                                <div class="progress-bar rounded-pill" role="progressbar" :style="{width: 25*(index+1) +'%'}"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---------- Form Button ---------->
                <button type="button" class="f_btn prev_btn text-uppercase position-absolute" id="prevBtn" style="display: none"><span><i class="fas fa-arrow-left"></i></span> SKIP THIS QUESTION
                </button>
                <button type="button" class="f_btn prev_btn text-uppercase position-absolute" @click.prevent="skipToNext()">
                    SKIP THIS
                    <span>
                        <i :class="loading ? 'fas fa-spinner fa-fw fa-xl margin-right-md fa-spin': 'fas fa-forward'"></i>
                    </span>
                </button>
                <button type="button" class="f_btn next_btn text-uppercase position-absolute" id="nextBtn"
                        @click.prevent="nextPrev(1)">NEXT STEP <span><i :class="loading ? 'fas fa-spinner fa-fw fa-xl margin-right-md fa-spin' : 'fas fa-arrow-right'"></i></span>
                </button>
            </div>
            <div v-show="currentTab >= 4">
                <div class="multisteps_form_panel">
                    <div class="question_title">
                        <h1 class="text-center py-5 animate__animated animate__fadeInRight animate_25ms">
                            Your action on this quiz has been completed.
                            <router-link class="text-center animate__animated animate__fadeInRight animate_25ms" to="/">Go to scoreboard</router-link>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Custom-js include -->


<script>
    import axios from 'axios'
    export default {
        name: 'HtmlQuiz',
        data(){
            return {
                loading:false,
                currentTab:0,
                quiz:[],
                user_given_answer:{
                    question_id:null,
                    option_id: null
                }
            }
        },
        mounted() {
            $(function () {
                "use strict";
                // ========== Form-select-option ========== //
                $(".step_1").on('click', function () {
                    $(".step_1").removeClass("active");
                    $(this).addClass("active");
                });
                $(".step_2").on('click', function () {
                    $(".step_2").removeClass("active");
                    $(this).addClass("active");
                });
                $(".step_3").on('click', function () {
                    $(".step_3").removeClass("active");
                    $(this).addClass("active");
                });
                $(".step_4").on('click', function () {
                    $(".step_4").removeClass("active");
                    $(this).addClass("active");
                });


            });
            this.getQuiz();

        },
        methods: {
            getQuiz: async function(){
                try {
                    let response = await axios.get('get_quiz/HTML');
                    //this.pageLoading = false
                    this.quiz = response.data.question;
                    this.quiz.forEach( (item,index) =>{
                        if(item.id == response.data.last_given_answer_question_id){
                            this.currentTab = index+1
                        }
                    })
                    setTimeout(()=>{
                        this.showTab(this.currentTab); // Display the current tab
                    },500)
                }catch (e) {
                    console.log('Error: '+e);
                }
            },
            select_answer: function(_option_index,_question_id, _option_id){

                this.$refs.option_list_item.forEach((_item)=>{
                    _item.classList.remove('active')
                });
                $(document).on('click','.option_li',function(){
                    $(this).addClass('active');
                })
                this.user_given_answer = {
                    question_id: _question_id,
                    option_id: _option_id
                }
                console.log(this.user_given_answer);
            },

            showTab: function (n) {
                // This function will display the specified tab of the form ...
                var x = document.getElementsByClassName("multisteps_form_panel");
                if(x[n]){
                    x[n].style.display = "block";
                }
                this.fixStepIndicator(n)
            },
            nextPrev: async function (n) {

                if(this.currentTab < 4){
                    if(!this.user_given_answer.option_id){
                        return false;
                    }
                    await this.submitAnswer();
                    this.user_given_answer.option_id = null;
                    var x = document.getElementsByClassName("multisteps_form_panel");
                    x[this.currentTab].style.display = "none";
                    this.currentTab = this.currentTab + n;
                    this.showTab(this.currentTab);
                }else{
                    console.log('Quiz ended')
                }
            },
            skipToNext: async function(){
                if(this.currentTab < 4){
                    this.user_given_answer = {
                        question_id: this.quiz[this.currentTab].id,
                        option_id: null
                    }
                    //console.log(this.user_given_answer);
                    await this.submitAnswer();
                    this.user_given_answer = {
                        question_id: null,
                        option_id: null
                    }
                    var x = document.getElementsByClassName("multisteps_form_panel");
                    x[this.currentTab].style.display = "none";
                    this.currentTab = this.currentTab + 1;
                    this.showTab(this.currentTab);
                }else{
                    console.log('Quiz ended')
                }
            },
            submitAnswer: async function(){
                try {
                    this.loading = true
                    let response = await axios.post('submit_answer',this.user_given_answer);
                    this.loading = false
                } catch (e) {
                    this.skipLoading = false
                    console.log('Server Error '+ e);
                }
            },
            fixStepIndicator: function (n) {
                // This function removes the "active" class of all steps...
                var i, x = document.getElementsByClassName("step");
                for (i = 0; i < x.length; i++) {
                    x[i].className = x[i].className.replace(" active", "");
                }
                //... and adds the "active" class to the current step:
                if(x[n]){
                    x[n].className += " active";
                }
            }
        }
    }
</script>
