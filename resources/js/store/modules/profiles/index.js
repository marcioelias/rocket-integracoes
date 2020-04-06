import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'
import Axios from 'axios'

const state = {
    ...validationState,
    userId: null,
    active: false,
    name: '',
    email: '',
    username: '',
    currentPassword: '',
    newPassword: '',
    newPasswordConfirmation: '',
    changePassword: false
}

const getters = {
    ...validationGetters,
}

const actions = {
    async loadUserProfile({commit}, id) {
        await axios.get('/json/users/' + id)
            .then(r => {
                if (r.status == 200) {
                    commit('setUserId', r.data.id)
                    commit('setName', r.data.name)
                    commit('setEmail', r.data.email)
                    commit('setUsername', r.data.username)
                    commit('setActive', r.data.active)
                }
            })
    },
    async changeUserPassword({commit, dispatch}) {
        let user = {
            password: state.newPassword,
            password_confirmation: state.newPasswordConfirmation,
            old_password: state.currentPassword
        }
        await axios.put('/json/users/'+state.userId+'/change_password', user)
            .then(r => {
                if (r.status === 200) {
                    swal({
                        title: 'Sucesso!',
                        text: 'Registro alterado.',
                        type: 'success',
                        confirmButtonText: 'Ok',
                        padding: '2em'
                    }).then(function(result) {
                        dispatch('clearForm')
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
    },
    clearForm({commit}) {
        commit('setChangePassword', false)
        commif('setCurrentPassword', '')
        commit('setNewPassword', '')
        commit('setNewPasswordConfirmation', '')
    }
}

const mutations = {
    ...validationMutations,
    setUserId(state, payload) {
        state.userId = payload
    },
    setActive(state, payload) {
        state.active = payload
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
    setCurrentPassword(state, payload) {
        state.currentPassword = payload
    },
    setNewPassword(state, payload) {
        state.newPassword = payload
    },
    setNewPasswordConfirmation(state, payload) {
        state.newPasswordConfirmation = payload
    },
    setChangePassword(state, payload) {
        state.changePassword = payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
