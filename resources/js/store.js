import Vue from 'vue'
import Vuex from 'vuex'

import webhooks from './store/modules/webhooks'
import events from './store/modules/events'
import endpoints from './store/modules/endpoints'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        webhooks,
        events,
        endpoints
    }
})
