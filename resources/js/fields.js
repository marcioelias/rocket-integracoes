import Vue from 'vue'
import store from './store'
import FieldCrudForm from './components/FieldCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#field-crud',
    store,
    components: {
        FieldCrudForm
    }
})
