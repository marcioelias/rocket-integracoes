import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    productId: null,
    name: '',
    webhookId: null,
    productCode: '',
    actions: [],
    deletedActions: [],
    webhooks: [],
    events: [],
    apis: [],
    variables: []
}

const getters = {
    ...validationGetters,
    actionByIdx: state => idx => {
        return state.actions[idx]
    },
}

const actions = {
    async storeProduct({commit}) {
        let product = {
            name: state.name,
            webhook_id: state.webhookId,
            product_code: state.productCode,
            actions: state.actions,
            deleted_actions: state.deletedActions
        }

        if (state.productId) {
            await axios.put('/products/'+state.productId, product)
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
            await axios.post('/products', product)
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
    async loadProduct({commit, dispatch}, id) {
        await axios.get('/json/products/'+id)
            .then(r => {
                if (r.status === 200) {
                    commit('setProductId', r.data.id)
                    commit('setName', r.data.name)
                    dispatch('selectWebhookId', r.data.webhook_id)
                    commit('setProductCode', r.data.product_code)
                    dispatch('loadActions', id)
                }
            }
        )
    },
    async loadActions({commit, dispatch}, product) {
        await axios.get('/json/products/'+product+'/actions')
            .then(r => {
                if (r.status === 200) {
                    commit('setActions', r.data)
                }
            }
        )
    },
    async loadWebhooks({commit,dispatch}) {
        await axios.get('/json/webhooks')
            .then(r => {
                commit('setWebhooks', r.data)
            }
        )
    },
    async loadEvents({commit}, id) {
        await axios.get(`/json/webhooks/${id}/events`)
            .then(r => {
                commit('setEvents', r.data)
            })
    },
    async loadApis({commit}) {
        await axios.get('/json/apis')
            .then(r => {
                commit('setApis', r.data)
            })
    },
    async loadVariables({commit}) {
        await axios.get('/json/all/fields')
            .then(r => {
                commit('setVariables', r.data)
            })
    },
    selectWebhookId({commit, dispatch}, id) {
        commit('setWebhookId', id)
        dispatch('loadEvents', id)
    }
}

const mutations = {
    ...validationMutations,
    setProductId(state, payload) {
        state.productId = payload
    },
    setProductCode(state, payload) {
        state.productCode = payload
    },
    setName(state, payload) {
        state.name = payload
    },
    setWebhookId(state, payload) {
        state.webhookId = payload
    },
    setWebhooks(state, payload) {
        state.webhooks = payload
    },
    setActions(state, payload) {
        state.actions = payload
    },
    setActionActive(state, payload) {
        state.actions[payload].active = !state.actions[payload].active
    },
    addAction(state, payload) {
        state.actions.push(payload)
    },
    updateAction(state, payload) {
        state.actions[payload.actionIdx] = payload.action
    },
    setEvents(state, payload) {
        state.events = payload
    },
    setApis(state, payload) {
        state.apis = payload
    },
    setVariables(state, payload) {
        state.variables = payload
    },
    removeAction(state, payload) {
        let act = state.actions[payload]
        if (act.id) {
            state.deletedActions.push(act.id)
        }
        state.actions.splice(payload, 1)
    }

}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
