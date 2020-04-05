import Vue from 'vue'
import store from './store'
import ProductCrudForm from './components/ProductCrudForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#product-crud',
    store,
    components: {
        ProductCrudForm
    }
})
