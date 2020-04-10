import Axios from 'axios'
import { jsonState, jsonActions, jsonMutations } from '../../../mixins/json'
import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...jsonState,
    ...validationState,
    name: '',
    relative_url: '',
    token: '',
    localFields: [],
    mappings: [],
    WebhookId: null,
}

const getters = {
        ...validationGetters,
        getLocalNotMappedFields: (state) => {
            return state.localFields.filter(localf => !state.mappings.some(m => m.localField.field_name == localf.field_name))
        },
        getRemoteFields: (state) => {
            return state.remoteFields
        },
        getMappings: (state) => {
            return state.mappings
        },
        getMappingByIndex: (state) => (mapping) => {
            return state.mappings.findIndex(e => e.localField.field_name === mapping.localField.field_name)
        },
        getLocalFieldByName: (state) => (name) => {
            return state.localFields.find(f => f.field_name === name)
        },
        getFinalFieldMapping: (state) => {
            return JSON.stringify(state.mappings)
        }
    }

const actions = {
        ...jsonActions,
        createFieldMapping({commit}, mapping) {
            commit('addNewMapping', mapping)
        },
        deleteFieldMapping({ commit, getters }, value) {
            commit('delMapping', getters.getMappingByIndex(value))
        },
        getLocalFields({commit}) {
            Axios.get('/json/fields')
                .then(r => commit('setLocalFields', r.data))
        },
        async storeWebhook({state, commit}) {
            let webhook = {
                name: state.name,
                relative_url: state.relative_url,
                token: state.token,
                json: state.jsonSchema,
                json_mapping: JSON.stringify(state.mappings)
            }

            if (state.webhookId) {
                await axios.put('/webhooks/'+state.webhookId, webhook)
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
                await axios.post('/webhooks', webhook)
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
        async loadWebhook({commit, dispatch}, id) {
            await Axios.get('/json/webhook/'+id)
                .then(r => {
                    if (r.status === 200) {
                        commit('setWebhookId', r.data.id)
                        commit('setName', r.data.name)
                        commit('setRelativeUrl', r.data.relative_url)
                        commit('setToken', r.data.token)
                        commit('setJsonSchema', r.data.json)
                        commit('setMappings', JSON.parse(r.data.json_mapping))
                        dispatch('extractJsonFields', r.data.json)
                    }
                })
        }

    }

const mutations = {
        ...jsonMutations,
        ...validationMutations,
        setLocalFields(state, payload) {
            state.localFields = payload
        },
        addNewMapping(state, payload) {
            state.mappings.push(payload)
        },
        delMapping(state, payload) {
            state.mappings.splice(payload, 1)
        },
        setRelativeUrl(state, payload) {
            state.relative_url = payload
        },
        setToken(state, payload) {
            state.token = payload
        },
        setName(state, payload) {
            state.name = payload
        },
        setMappings(state, payload) {
            state.mappings = payload
        },
        setWebhookId(state, payload) {
            state.webhookId = payload
        },
        updateFieldMapping(state, payload) {
            state.mappings[payload.id].localField = payload.localField
            state.mappings[payload.id].function = payload.function
            state.mappings[payload.id].remoteField = payload.remoteField
        }
    }

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
