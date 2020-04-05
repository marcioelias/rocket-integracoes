import Vue from 'vue'
import store from './store'
import UserCrudForm from './components/UserCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#user-crud',
    store,
    components: {
        UserCrudForm
    }
})
