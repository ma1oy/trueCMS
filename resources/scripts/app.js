// require('./global/plugins');
// require('./global/interceptors');
// require('./global/components');
// require('./global/directives');
// require('./global/polyfills');
// require('./animation');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.options.emulateHTTP = true;