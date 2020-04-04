import Vue from 'vue'
import store from './store'
import ActionsCrudForm from './components/ActionCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#actions-crud',
    store,
    components: {
        ActionsCrudForm
    }
})
