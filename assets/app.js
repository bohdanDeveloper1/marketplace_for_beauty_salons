import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import './styles/app.css';

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));