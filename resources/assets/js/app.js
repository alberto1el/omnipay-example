
require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate';
Vue.use(VueRouter);
Vue.use(Vuelidate);

import paymentForm from './components/PaymentFormComponent.vue';
import successPage from './components/PageSuccessComponent.vue';

const routes = [
    { path: '/', name:'paymentForm', component: paymentForm, props: { cart_products: windowCart }},
    { path: '/success', name:'successPage', component: successPage, props: true}
];

const router = new VueRouter({
    routes
});

const app = new Vue({
    router
}).$mount('#main')
