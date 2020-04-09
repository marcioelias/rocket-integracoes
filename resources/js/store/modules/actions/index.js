import Axios from 'axios'
import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    actionId: null,
    productId: null,
    eventId: null,
    apiId: null,
    endpointId: null,
    actionData: {},
    triggerData: '',
    active: true,
    products: [],
    events: [],
    apis: [],
    endpoints: [],
    searchVariable: '',
    variables: [],
    delay: 0,
    delayType: 'minute',
    delayTypes: [
        {
            type: 'minute',
            label: 'Minutos',
            multiplier: 60
        },
        {
            type: 'hour',
            label: 'Horas',
            multiplier: 3600
        },
        {
            type: 'day',
            label: 'Dias',
            multiplier: 86400
        }
    ],
    productLoading: false,
    eventLoading: false,
    apiLoading: false,
    endpointLoading: false
}

const getters = {
    ...validationGetters,
    selectedEvent: state => {
        return state.events.find(e => e.id == state.eventId)
    },
    selectedEndpoint: state => {
        return state.endpoints.find(e => e.id == state.endpointId)
    },
    filteredVariables: (state) => {
        if (state.searchVariable.length >= 2) {
            return state.variables.filter(v => v.field_name.toLowerCase().search(state.searchVariable.toLowerCase()) >= 0 || v.label.toLowerCase().search(state.searchVariable.toLowerCase()) >= 0)
        } else {
            return state.variables
        }
    },
    getMetaFields: (state, getters) => {
        let ep = getters.selectedEndpoint
        let m = ep ? JSON.parse(ep.json) : []
        return m.filter(e => e.meta == true)
    }
}

const actions = {
    async storeAction({commit}) {
        let action = {
            product_id: state.productId,
            event_id: state.eventId,
            delay: state.delay,
            delay_type: state.delayType,
            api_endpoint_id: state.endpointId,
            data: JSON.stringify(state.actionData),
            trigger_data: state.triggerData,
            active: state.active
        }

        if (state.actionId) {
            await axios.put('/actions/'+state.actionId, action)
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
            await axios.post('/actions', action)
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
    async loadAction({commit, dispatch}, id) {
        await Axios.get('/json/actions/'+id)
            .then(r => {
                if (r.status === 200) {
                    console.log(r.data);
                    dispatch('selectProduct', r.data.product_id)
                    commit('setEventId', r.data.event_id)
                    dispatch('selectApi', r.data.api_endpoint.api_id)
                    commit('setEndpointId', r.data.api_endpoint_id)
                    commit('setDelay', r.data.delay)
                    commit('setDelayType', r.data.delay_type)
                    commit('setActionData', JSON.parse(r.data.data))
                    commit('setActive', r.data.active)
                    commit('setTriggerData', r.data.trigger_data)
                }
            }
        )
    },
    async loadProducts({commit}) {
        commit('setProductLoading', true)
        await Axios.get('/json/products')
            .then(r => {
                commit('setProducts', r.data)
                commit('setProductLoading', false)
            })
    },
    async loadEvents({commit}, product) {
        commit('setEventLoading', true)
        await Axios.get('/json/events/product/'+product)
            .then(r => {
                commit('setEvents', r.data)
                commit('setEventLoading', false)
            })
    },
    async loadApis({commit}) {
        commit('setApiLoading', true)
        await Axios.get('/json/apis')
            .then(r => {
                commit('setApis', r.data)
                commit('setApiLoading', false)
            })
    },
    async loadEndpoints({commit}, api) {
        commit('setEndpointLoading', true)
        await Axios.get('/json/api_endpoints/api/'+api)
            .then(r => {
                commit('setEndpoints', r.data)
                commit('setEndpointLoading', false)
            })
    },
    async loadVariables({commit}) {
        await Axios.get('/json/all/fields')
            .then(r => {
                commit('setVariables', r.data)
            })
    },
    selectProduct({commit, dispatch}, id) {
        commit('setProductId', id)
        dispatch('loadEvents', id)
    },
    selectApi({commit, dispatch}, id) {
        commit('setApiId', id)
        dispatch('loadEndpoints', id)
    }
}

const mutations = {
    ...validationMutations,
    setProductId(state, payload) {
        state.productId = payload
    },
    setEventId(state, payload) {
        state.eventId = payload
    },
    setApiId(state, payload) {
        state.apiId = payload
    },
    setEndpointId(state, payload) {
        state.endpointId = payload
    },
    setActionData(state, payload) {
        state.actionData = payload
    },
    setActive(state, payload) {
        state.active = payload
    },
    setDelay(state, payload) {
        state.delay = payload
    },
    setDelayType(state, payload) {
        state.delayType = payload
    },
    setProducts(state, payload) {
        state.products = payload
    },
    setEvents(state, payload) {
        state.events = payload
    },
    setApis(state, payload) {
        state.apis = payload
    },
    setEndpoints(state, payload) {
        state.endpoints = payload
    },
    setVariables(state, payload) {
        state.variables = payload
    },
    setTriggerData(state, payload) {
        state.triggerData = payload
    },
    setSearchVariable(state, payload) {
        state.searchVariable = payload
    },
    setProductLoading(state, payload) {
        state.productLoading = payload
    },
    setEventLoading(state, payload) {
        state.eventLoading = payload
    },
    setApiLoading(state, payload) {
        state.apitLoading = payload
    },
    setEndpointLoading(state, payload) {
        state.endpointLoading = payload
    },
    setActionData(state, payload) {
        state.actionData = payload
    },
    setActionId(state, payload) {
        state.actionId = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
