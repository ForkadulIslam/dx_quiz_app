<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="btn-group d-flex">
                    <router-link class="btn f_btn home_btn text-uppercase" to="/">HOME</router-link>
                    <router-link class="btn f_btn topics_btn text-uppercase" to="/php">PHP</router-link>
                    <router-link class="btn f_btn topics_btn text-uppercase" to="/ajax">AJAX</router-link>
                    <router-link class="btn f_btn topics_btn text-uppercase" to="/jquery">jQuery</router-link>
                    <router-link class="btn f_btn topics_btn text-uppercase" to="/html">HTML</router-link>
                </div>
            </div>
        </div>
        <router-view />
    </div>
</template>

<script>
    export default {
        name: 'App'
    }
</script>
