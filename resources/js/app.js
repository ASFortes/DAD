/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(VueRouter)
Vue.use(BootstrapVue);


import VueSocketIO from "vue-socket.io"
Vue.use(
 new VueSocketIO({
 debug: true,
 connection: "http://127.0.0.1:8080"
 })
)

import App from './App.vue'
import VueRouter from 'vue-router'
import ProductComponent from './components/product'
import WelcomeComponent from './components/welcome'
import BootstrapVue from 'bootstrap-vue'
import LoginComponent from './components/login'
import EditComponent from './components/userEdit'
import RegisterComponent from './components/register'
import ShopCartComponent from './components/shopCart'
import store from "./stores/global-storage"
import OrdersInProgress from "./components/ordersInProgress"


const routes = [
    { path: '/', redirect: '/home' },
    { path: '/products', component: ProductComponent },
    { path: '/login', component: LoginComponent },
    { path: '/home', component:WelcomeComponent},
    { path: '/userEdit', component:EditComponent},
    { path: '/register', component:RegisterComponent},
    { path: '/cart', component:ShopCartComponent},
    { path: '/ordersInProgress', component:OrdersInProgress},


   
        
   
]

const router = new VueRouter({
    routes: routes
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    render:h=>h(App),
    router,
    store,
     data:{
         products:[],
     },
     created () {
        this.$store.dispatch('loadUserLogged')
    }
  
}).$mount('#app');



