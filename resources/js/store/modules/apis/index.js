import Axios from 'axios'
import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    apiId: null,
    name: '',
    baseUrl: '',
    authentication: 'none',
    token: '',
    username: '',
    password: '',
    authentications: [
        {
            name: 'none',
            label: 'Desabilitada'
        },
        {
            name: 'token',
            label: 'Token'
        },
        /* ainda não implementado */
        /* {
            name: 'jwt',
            label: 'JWT'
        }, */
    ]
}

const getters = {
    ...validationGetters,
}

const actions = {
    async storeApi({commit}) {
        let api = {
            name: state.name,
            base_url: state.baseUrl,
            auth_method: state.authentication,
            token: state.token,
            username: state.username,
            password: state.password
        }

        if (state.apiId) {
            await axios.put('/apis/'+state.apiId, api)
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
            await axios.post('/apis', api)
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
    async loadApi({commit}, id) {
        await Axios.get('/json/apis/'+id)
            .then(r => {
                if (r.status === 200) {
                    commit('setApiId', r.data.id)
                    commit('setName', r.data.name)
                    commit('setBaseUrl', r.data.base_url)
                    commit('setAuthentication', r.data.auth_method)
                    commit('setToken', r.data.token)
                    commit('setUsername', r.data.username)
                    commit('setPassword', r.data.password)
                }
            }
        )
    },
}

const mutations = {
    ...validationMutations,
    setApiId(state, payload) {
        state.apiId = payload
    },
    setName(state, payload) {
        state.name = payload
    },
    setBaseUrl(state, payload) {
        state.baseUrl = payload
    },
    setAuthentication(state, payload) {
        state.authentication = payload
    },
    setToken(state, payload) {
        state.token = payload
    },
    setUsername(state, payload) {
        state.username = payload
    },
    setPassword(state, payload) {
        state.password = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
