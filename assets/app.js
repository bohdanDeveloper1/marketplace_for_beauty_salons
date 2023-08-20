/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import Vue from 'vue';
import BootstrapVue from "bootstrap-vue";
import {Vuelidate} from "vuelidate";
import TestComponent from "./js/components/TestComponent.vue";
import ValidatorVuelidate from "./js/components/ValidatorVuelidate.vue";
import './styles/app.css';

Vue.use(BootstrapVue);
Vue.use(Vuelidate);
new Vue({
    el: '#app',
    // your Vue component options here
    components: {
        TestComponent,
        ValidatorVuelidate,
    }
});