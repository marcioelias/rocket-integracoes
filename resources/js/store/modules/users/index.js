import { validationState, validationGetters, validationActions, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    userId: null,
    name: '',
    email: '',
    username: '',
    password: '',
    passwordConfirmation: '',
    active: true
}

const getters = {
    ...validationGetters,
}

const actions = {
    async storeUser({commit}) {
        let user = {
            name: state.name,
            email: state.email,
            username: state.username,
            password: state.password,
            password_confirmation: state.passwordConfirmation,
            active: state.active,
        }

        if (state.userId) {
            await axios.put('/users/'+state.userId, user)
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
            await axios.post('/users', user)
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
    async loadUser({commit}, id) {
        await axios.get('/json/users/'+id)
            .then(r => {
                if (r.status === 200) {
                    commit('setUserId', r.data.id)
                    commit('setName', r.data.name)
                    commit('setEmail', r.data.email)
                    commit('setUsername', r.data.username)
                    commit('setActive', r.data.active)
                }
            }
        )
    },
}

const mutations = {
    ...validationMutations,
    setUserId(state, payload) {
        state.userId = payload
    },
    setName(state, payload) {
        state.name = payload
    },
    setEmail(state, payload) {
        state.email = payload
    },
    setUsername(state, payload) {
        state.username = payload
    },
    setPassword(state, payload) {
        state.password = payload
    },
    setPasswordConfirmation(state, payload) {
        state.passwordConfirmation = payload
    },
    setActive(state, payload) {
        state.active = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
