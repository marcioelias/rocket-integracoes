import Axios from 'axios'
import { jsonState, jsonActions, jsonMutations } from '../../../mixins/json'

const state = {
    ...jsonState,
    eventId: null,
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
    async loadWebhooks({commit, dispatch, state}) {
        await Axios.get('/json/webhooks')
            .then(r => {
                commit('setWebhooks', r.data)
                if (state.eventId) {
                    dispatch('loadEvent', state.eventId)
                }
            }
        )
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

        if (state.eventId) {
            await axios.put('/events/'+state.eventId, event)
                .then(r => {
                    if (r.status === 200) {
                        swal({
                            title: 'Sucesso!',
                            text: 'Registro alterado.',
                            type: 'success',
                            confirmButtonText: 'Ok',
                            padding: '2em'
                        }).then(function(result) {
                            window.location =  r.data.redirect
                        })
                    }
                })
                .catch(e => {
                    switch (e.response.status) {
                        case 422:
                            swal({
                                title: 'Ooops!',
                                text: 'Algo deu errado.',
                                type: 'error',
                                confirmButtonText: 'Ok',
                                padding: '2em'
                            }).then(function(result) {
                                commit('setHttpErrors', e.response.data.errors)
                            })
                            break;

                        default:
                            swal({
                                title: 'Ooops!',
                                text: 'Algo deu errado.',
                                type: 'error',
                                confirmButtonText: 'Ok',
                                padding: '2em'
                            })
                            break;
                    }
                })
        } else {
            await axios.post('/events', event)
            .then(r => {
                if (r.status === 200) {
                    swal({
                        title: 'Sucesso!',
                        text: 'Registro incluído.',
                        type: 'success',
                        confirmButtonText: 'Ok',
                        padding: '2em'
                    }).then(function(result) {
                        window.location =  r.data.redirect
                    })
                }
            })
            .catch(e => {
                switch (e.response.status) {
                    case 422:
                        swal({
                            title: 'Ooops!',
                            text: 'Algo deu errado.',
                            type: 'error',
                            confirmButtonText: 'Ok',
                            padding: '2em'
                        }).then(function(result) {
                            commit('setHttpErrors', e.response.data.errors)
                        })
                        break;

                    default:
                        swal({
                            title: 'Ooops!',
                            text: 'Algo deu errado.',
                            type: 'error',
                            confirmButtonText: 'Ok',
                            padding: '2em'
                        })
                        break;
                }
            })
        }
    },
    async loadEvent({commit, dispatch}, id) {
        await Axios.get('/json/events/'+id)
            .then(r => {
                if (r.status === 200) {
                    dispatch('selectWebhook', r.data.webhook_id)
                    commit('setName', r.data.name)
                    commit('setConditions', JSON.parse(r.data.conditions))
                }
            }
        )
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
    },
    setConditions(state, payload) {
        state.conditions = payload
    },
    setEventId(state, payload) {
        state.eventId = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
