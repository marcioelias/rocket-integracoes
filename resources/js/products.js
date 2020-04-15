import Vue from 'vue'
import store from './store'
import ProductCrudForm from './components/ProductCrudForm.vue'
import ProductActionsCrudForm from './components/ProductActionsCrudForm.vue'
import ProductActionsTable from './components/ProductActionsTable.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#product-crud',
    store,
    components: {
        ProductCrudForm,
        ProductActionsCrudForm,
        ProductActionsTable
    }
})
