import Vue from 'vue'
import Vuex from 'vuex'

import webhooks from './store/modules/webhooks'
import events from './store/modules/events'
import endpoints from './store/modules/endpoints'
import actions from './store/modules/actions'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        webhooks,
        events,
        endpoints,
        actions
    }
})
