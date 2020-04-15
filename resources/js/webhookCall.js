import Vue from 'vue'
import VueJsonPretty from 'vue-json-pretty'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#webhook-calls',
    components: {
        VueJsonPretty
    }
})
