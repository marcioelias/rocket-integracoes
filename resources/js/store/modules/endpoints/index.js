import Axios from 'axios'
import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    name: '',
    apiId: null,
    relativeUrl: '',
    httpMethod: 'GET',
    formBody: '',
    formDataField: '',
    fieldOk: '',
    codeOk: '',
    fields: [],
    apis: [],
    httpMethods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    formBodys: ['form-data', 'raw'],
    apiEndpointId: null,
    variables: [],
    searchVariable: ''
}

const getters = {
    ...validationGetters,
    filteredVariables: (state) => {
        if (state.searchVariable.length >= 2) {
            return state.variables.filter(v => v.field_name.toLowerCase().search(state.searchVariable.toLowerCase()) >= 0 || v.label.toLowerCase().search(state.searchVariable.toLowerCase()) >= 0)
        } else {
            return state.variables
        }
    }
}

const actions = {
    async loadApis({commit}) {
        await Axios.get('/json/apis')
            .then(r => {
                commit('setApis', r.data)
            })
    },
    async loadVariables({commit}) {
        await Axios.get('/json/fields/all')
            .then(r => {
                commit('setVariables', r.data)
            })
    },
    async storeApiEndpoint({commit}) {
        let endpoint = {
            name: state.name,
            api_id: state.apiId,
            relative_url: state.relativeUrl,
            method: state.httpMethod,
            json: JSON.stringify(state.fields),
            body: state.formBody,
            data_field: state.formDataField,
            field_ok: state.fieldOk,
            code_ok: state.codeOk
        }

        if (state.apiEndpointId) {
            await axios.put('/api_endpoints/'+state.apiEndpointId, endpoint)
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
            await axios.post('/api_endpoints', endpoint)
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
    },
    async loadApiEndpoint({commit}, id) {
        console.log(id);
        await Axios.get('/json/api_endpoint/'+id)
                .then(r => {
                    if (r.status === 200) {
                        commit('setApiEndpointId', r.data.id)
                        commit('setApiId', r.data.api_id)
                        commit('setName', r.data.name)
                        commit('setRelativeUrl', r.data.relative_url)
                        commit('setHttpMethod', r.data.method)
                        commit('setFormBody', r.data.body)
                        commit('setFormDataField', r.data.data_field)
                        commit('setFields', JSON.parse(r.data.json))
                        commit('setFieldOk', r.data.field_ok)
                        commit('setCodeOk', r.data.code_ok)
                    }
                })
    }
}

const mutations = {
    ...validationMutations,
    setName(state, payload) {
        state.name = payload
    },
    setApiId(state, payload) {
        state.apiId = payload
    },
    setRelativeUrl(state, payload) {
        state.relativeUrl = payload
    },
    setHttpMethod(state, payload) {
        state.httpMethod = payload
    },
    setFormBody(state, payload) {
        state.formBody = payload
    },
    setFormDataField(state, payload) {
        state.formDataField = payload
    },
    addField(state, payload) {
        state.fields.push(payload)
    },
    delField(state, payload) {
        state.fields.splice(payload, 1)
    },
    setApis(state, payload) {
        state.apis = payload
    },
    setFields(state, payload) {
        state.fields = payload
    },
    setApiEndpointId(state, payload) {
        state.apiEndpointId = payload
    },
    setVariables(state, payload) {
        state.variables = payload
    },
    setSearchVariable(state, payload) {
        state.searchVariable = payload
    },
    setFieldOk(state, payload) {
        state.fieldOk = payload
    },
    setCodeOk(state, payload) {
        state.codeOk = payload
    }

}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
