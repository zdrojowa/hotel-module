import Vue from 'vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
import draggable from 'vuedraggable';
import { BootstrapVue, IconsPlugin, FormRatingPlugin } from 'bootstrap-vue';
import MultiSelect from 'vue-multiselect'

window.axios = require('axios');

Vue.use(CKEditor);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(FormRatingPlugin);

Vue.component('multiselect', MultiSelect);
Vue.component('draggable', draggable);
Vue.component('media-selector', require('./components/media-selector.vue').default);
Vue.component('nested', require('./components/nested.vue').default);
Vue.component('list', require('./components/list.vue').default);
Vue.component('wellness', require('./components/wellness.vue').default);
Vue.component('apartment', require('./components/apartment.vue').default);
Vue.component('convenience', require('./components/convenience.vue').default);
Vue.component('files', require('./components/files.vue').default);
Vue.component('socials', require('./components/socials.vue').default);
Vue.component('info', require('./components/info.vue').default);
Vue.component('hotel', require('./components/hotel.vue').default);
Vue.component('kitchen-type', require('./components/kitchen-type.vue').default);
Vue.component('kitchen', require('./components/kitchen.vue').default);
Vue.component('awards', require('./components/awards.vue').default);
Vue.component('description', require('./components/description.vue').default);
Vue.component('gallery', require('./components/gallery.vue').default);
Vue.component('spa', require('./components/spa.vue').default);
Vue.component('spa-gallery', require('./components/spa-gallery.vue').default);
Vue.component('spa-highlights', require('./components/spa-highlights.vue').default);

const app = new Vue({
    el: '#app'
});

