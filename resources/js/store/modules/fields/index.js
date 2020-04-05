import { validationState, validationGetters, validationMutations } from '../../../mixins/validation'

const state = {
    ...validationState,
    fieldId: null,
    name: '',
    label: ''
}

const getters = {
    ...validationGetters,
}

const actions = {
    async storeField({commit}) {
        let fieldData = {
            field_name: state.name,
            label: state.label
        }

        console.log(fieldData)

        if (state.fieldId) {
            await axios.put('/fields/'+state.fieldId, fieldData)
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
            await axios.post('/fields', fieldData)
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
    async loadField({commit}, id) {
        await axios.get('/json/fields/'+id)
            .then(r => {
                if (r.status === 200) {
                    commit('setFieldId', r.data.id)
                    commit('setName', r.data.field_name)
                    commit('setLabel', r.data.label)
                }
            }
        )
    },
}

const mutations = {
    ...validationMutations,
    setFieldId(state, payload) {
        state.fieldId = payload
    },
    setName(state, payload) {
        state.name = payload
    },
    setLabel(state, payload) {
        state.label =  payload
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
