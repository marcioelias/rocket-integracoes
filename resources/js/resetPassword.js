import Vue from 'vue'
import SendEmailResetPassword from './components/SendEmailResetPassword.vue'
import ResetPasswordForm from './components/ResetPasswordForm.vue'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

new Vue({
    el: '#reset-password',
    components: {
        SendEmailResetPassword,
        ResetPasswordForm
    }
})
