import Vue from 'vue';
import draggable from 'vuedraggable';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

window.axios = require('axios');

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component('draggable', draggable);
Vue.component('nested', require('./components/nested.vue').default);
Vue.component('list', require('./components/list.vue').default);

const app = new Vue({
    el: '#app'
});

