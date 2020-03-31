import Axios from 'axios'
import { jsonState, jsonActions, jsonMutations } from '../../../mixins/json'

const state = {
    ...jsonState,
    name: '',
    conditions: [],
    webhookId: null,
    webhooks: [],
    eventId: null
}

const getters = {
    getWebhooks: (state) => {
        return state.webhooks
    },
    getWebhookById: (state) => (id) => {
        return state.webhooks.find(w => w.id === id)
    }
}

const actions = {
    ...jsonActions,
    async loadWebhooks({commit}) {
        await Axios.get('/json/webhooks')
            .then(r => {
                commit('setWebhooks', r.data)
            })
    },
    selectWebhook({commit, dispatch, getters}, id) {
        commit('setWebhookId', id);
        dispatch('extractJsonFields', getters.getWebhookById(id).json)
    },
    async storeEvent({state, commit}) {
        let event = {
            name: state.name,
            webhook_id: state.webhookId,
            conditions: JSON.stringify(state.conditions)
        }

        console.log(event)
        if (state.eventId) {
            await axios.put('/events/'+state.eventId, event)
                .then(r => {
                    if (r.status === 200) {
                        window.location =  r.data.redirect
                    }
                })
                .catch(e => {
                    switch (e.response.status) {
                        case 422:
                            commit('setHttpErrors', e.response.data.errors)
                            break;

                        default:
                            break;
                    }
                })
        } else {
            await axios.post('/events', event)
                .then(r => {
                    if (r.status === 200) {
                        window.location =  r.data.redirect
                    }
                })
                .catch(e => {
                    switch (e.response.status) {
                        case 422:
                            commit('setHttpErrors', e.response.data.errors)
                            break;

                        default:
                            break;
                    }
                })
        }
    }
}

const mutations = {
    ...jsonMutations,
    setWebhooks(state, payload) {
        state.webhooks = payload
    },
    setWebhookId(state, payload) {
        state.webhookId = payload
    },
    setName(state, payload) {
        state.name = payload
    },
    addCondition(state, payload) {
        state.conditions.push(payload)
    },
    delCondition(state, payload) {
        state.conditions.splice(payload, 1)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
