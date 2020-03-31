export const jsonState = {
        jsonSchema: null,
        remoteFields: []
    }

export const jsonActions = {
        extractJsonFields({state, commit}, value) {
            var fields = []
            let f2 = (o, p) => {
                Object.keys(o).forEach(k => {
                    if (o[k] instanceof Object) {
                        f2(o[k], p != '' ? p+'.'+k : k)
                        //f2(o[k], k)
                    } else {
                        let v = p != '' ? p+'.'+k : k
                        switch (typeof  o[k]) {
                            case 'object':
                                fields.push({
                                    'type': 'object',
                                    'field': v
                                })
                                break;

                            case 'boolean':
                                fields.push({
                                    'type': 'boolean',
                                    'field': v
                                })
                                break;

                            case 'number':
                                fields.push({
                                    'type': 'number',
                                    'field': v
                                })
                                break;

                            case 'string':
                                fields.push({
                                    'type': 'string',
                                    'field': v
                                })
                                break;

                            default:
                                break;
                        }
                    }
                })
            }

            commit('setJsonSchema', value)

            f2(JSON.parse(state.jsonSchema), '')

            commit('setRemoteFields', fields)
        },
    }

export const jsonMutations = {
        setRemoteFields(state, payload) {
            payload.forEach(field => state.remoteFields.push(field))
        },
        setJsonSchema(state, payload) {
            state.jsonSchema = payload
        },
    }
