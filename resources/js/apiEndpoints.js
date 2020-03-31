import Vue from 'vue'
import store from './store'
import ApiEndpointsCrudForm from './components/ApiEndpointsCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#api-endpoints',
    store,
    components: {
        ApiEndpointsCrudForm
    }
})
