import Vue from 'vue'
import JsonFormComponent from './components/JsonFormComponent.vue'
import EventCrudForm from './components/EventCrudForm.vue'
import store from './store'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#teste',
    store,
    components: {
        JsonFormComponent,
        EventCrudForm
    }
})
