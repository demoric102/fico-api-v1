


require('./bootstrap');

window.Vue = require('vue');


import Vue from 'vue';


Vue.component('admin-component', require('./components/ExampleComponent.vue'));

Vue.component('misses-component', require('./components/MissesComponent.vue'));

Vue.component('hits-component', require('./components/HitsComponent.vue'));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app'
});
