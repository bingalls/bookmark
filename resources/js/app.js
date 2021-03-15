require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';

Vue.component('bookmark-component', require('./components/Bookmark.vue').default);

window.Fire = new Vue();

const app = new Vue({
    el: '#app',
});
