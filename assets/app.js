import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import './styles/app.css';

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

// чи можна додати import {App} from "vue"; щоб використовувати Pinia
// import { createApp } from 'vue'
// import { createPinia } from 'pinia'
// import {App} from "vue";
//
// const pinia = createPinia()
// const app = createApp(App)
// app.use(pinia)
// app.mount('#app')


// document.addEventListener('vue:before-mount', (event) => {
//     const {
//         componentName, // The Vue component's name
//         component, // The resolved Vue component
//         props, // The props that will be injected to the component
//         app, // The Vue application instance
//     } = event.detail;
//
//     // Example with Vue Router
//     // const router = VueRouter.createRouter({
//     //     history: VueRouter.createWebHashHistory(),
//     //     routes: [
//     //         { path: '/survey/test', component: Hello },
//     //     ],
//     // });
//     //
//     // app.use(router);
// });