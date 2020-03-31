export const validationState = {
    httpErrors: []
}

export const validationGetters = {
    getHttpErrors: (state) => {
        return state.httpErrors
    }
}

export const validationMutations = {
    setHttpErrors(state, payload) {
        state.httpErrors = payload
    },
}
