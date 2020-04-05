import Vue from 'vue'
import store from './store'
import ApiCrudForm from './components/ApiCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#api-crud',
    store,
    components: {
        ApiCrudForm
    }
})
