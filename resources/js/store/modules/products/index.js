import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    productId: null,
    name: '',
    webhookId: null,
    productCode: '',
    webhooks: []
}

const getters = {
    ...validationGetters,
}

const actions = {
    async loadWebhooks({commit}) {
        await axios.get('/json/webhooks')
            .then(r => {
                commit('setWebhooks', r.data)
            }
        )
    },
    async storeProduct({commit}) {
        let product = {
            name: state.name,
            webhook_id: state.webhookId,
            product_code: state.productCode
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
    async loadProduct({commit}, id) {
        await axios.get('/json/products/'+id)
            .then(r => {
                if (r.status === 200) {
                    commit('setProductId', r.data.id)
                    commit('setName', r.data.name)
                    commit('setWebhookId', r.data.webhook_id)
                    commit('setProductCode', r.data.product_code)
                }
            }
        )
    },
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
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
