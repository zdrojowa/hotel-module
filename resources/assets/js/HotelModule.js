import Vue from 'vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
import draggable from 'vuedraggable';
import { BootstrapVue, IconsPlugin, FormRatingPlugin } from 'bootstrap-vue';
import MultiSelect from 'vue-multiselect'
import { Datetime } from 'vue-datetime';

window.axios = require('axios');

Vue.use(CKEditor);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(FormRatingPlugin);

Vue.component('multiselect', MultiSelect);
Vue.component('draggable', draggable);
Vue.component('datetime', Datetime);
Vue.component('media-selector', require('./components/media-selector.vue').default);
Vue.component('nested', require('./components/nested.vue').default);
Vue.component('list', require('./components/list.vue').default);
Vue.component('wellness', require('./components/wellness.vue').default);
Vue.component('apartment', require('./components/apartment.vue').default);
Vue.component('icon', require('./components/icon.vue').default);
Vue.component('files', require('./components/files.vue').default);
Vue.component('socials', require('./components/socials.vue').default);
Vue.component('info', require('./components/info.vue').default);
Vue.component('hotel', require('./components/hotel.vue').default);
Vue.component('kitchen-type', require('./components/kitchen-type.vue').default);
Vue.component('kitchen', require('./components/kitchen.vue').default);
Vue.component('description', require('./components/description.vue').default);
Vue.component('animals', require('./components/animals.vue').default);
Vue.component('gallery', require('./components/gallery.vue').default);
Vue.component('contact', require('./components/contact.vue').default);
Vue.component('gallery-with-labels', require('./components/gallery-with-labels.vue').default);
Vue.component('work-time', require('./components/work-time.vue').default);
Vue.component('main-info', require('./components/main-info.vue').default);
Vue.component('price', require('./components/price.vue').default);
Vue.component('conference', require('./components/conference.vue').default);
Vue.component('hall', require('./components/hall.vue').default);
Vue.component('configuration', require('./components/configuration.vue').default);
Vue.component('attraction', require('./components/attraction.vue').default);
Vue.component('suggestion', require('./components/suggestion.vue').default);
Vue.component('editor', require('./components/editor.vue').default);
Vue.component('program', require('./components/program.vue').default);
Vue.component('conditions', require('./components/conditions.vue').default);
Vue.component('pack', require('./components/pack.vue').default);
Vue.component('convenience', require('./components/convenience.vue').default);
Vue.component('objects', require('./components/objects.vue').default);
Vue.component('customer-alliance', require('./components/customer-alliance.vue').default);
Vue.component('meals', require('./components/meals.vue').default);

const app = new Vue({
    el: '#app'
});

