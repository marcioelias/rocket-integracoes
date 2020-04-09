import Vue from 'vue'
import ShortUrlConfigForm from './components/ShortUrlConfigForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#short-url',
    components: {
        ShortUrlConfigForm
    }
})
