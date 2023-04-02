import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import PhpQuiz from './components/PhpQuiz';
import AjaxQuiz from "./components/AjaxQuiz";
import HtmlQuiz from "./components/HtmlQuiz";
import JqueryQuiz from "./components/JqueryQuiz";
import Log from "./middleware/Log";
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/php',
            name: 'php',
            component: PhpQuiz,
            meta: {
                middleware: [Log],
            },
        },
        {
            path: '/ajax',
            name: 'ajax',
            component: AjaxQuiz
        },
        {
            path: '/jquery',
            name: 'jquery',
            component: JqueryQuiz
        },
        {
            path: '/html',
            name: 'html',
            component: HtmlQuiz
        },
    ]
});

export default router;
