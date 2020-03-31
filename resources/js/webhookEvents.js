import Vue from 'vue'
import EventCrud from './components/EventCrudForm.vue'
import store from './store'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#webhook-events',
    store,
    components: {
        EventCrud
    }
})
