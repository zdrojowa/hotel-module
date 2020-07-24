import Vue from 'vue';
import draggable from 'vuedraggable';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import MultiSelect from 'vue-multiselect'

window.axios = require('axios');

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component('multiselect', MultiSelect);
Vue.component('draggable', draggable);
Vue.component('media-selector', require('./components/media-selector.vue').default);
Vue.component('nested', require('./components/nested.vue').default);
Vue.component('list', require('./components/list.vue').default);
Vue.component('wellness', require('./components/wellness.vue').default);
Vue.component('apartment', require('./components/apartment.vue').default);
Vue.component('convenience', require('./components/convenience.vue').default);
Vue.component('files', require('./components/files.vue').default);

const app = new Vue({
    el: '#app'
});

