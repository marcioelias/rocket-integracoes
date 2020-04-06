import Vue from 'vue'
import store from './store'
import UserProfileForm from './components/UserProfileForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#user-profile',
    store,
    components: {
        UserProfileForm
    }
})
