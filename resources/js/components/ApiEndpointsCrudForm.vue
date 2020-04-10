<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <!-- API -->
                <label for="api">API</label>
                <select id="api" class="form-control" v-model="apiId">
                    <option v-for="api in apis" :key="api.id" :value="api.id">{{ api.name }}</option>
                </select>
                <span v-if="getHttpErrors.api_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.api_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
               <label for="name">Nome</label>
               <input type="text" class="form-control" name="name" id="name" v-model="name">
               <span v-show="getHttpErrors.hasOwnProperty('name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
                <!-- name -->
                <label for="relative_url">URL Relativa</label>
                <input id="relative_url" name="relative_url" type="text" class="form-control" v-model="relativeUrl">
                <span v-if="getHttpErrors.hasOwnProperty('relative_url')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.relative_url" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <!-- Método HTTP -->
                <label for="method">Método</label>
                <select id="method" class="form-control" v-model="httpMethod">
                    <option v-for="(m, index) in httpMethods" :key="index" :value="m">{{ m }}</option>
                </select>
                <span v-if="getHttpErrors.hasOwnProperty('method')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.method" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
                <!-- Corpo -->
                <label for="body">Corpo</label>
                <select id="body" class="form-control" v-model="formBody">
                    <option v-for="(f, index) in formBodys" :key="index" :value="f">{{ f }}</option>
                </select>
                <span v-if="getHttpErrors.hasOwnProperty('body')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.body" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
                <!-- Campo de dados -->
                <label for="data_field">Campo de Dados</label>
                <input id="data_field" name="data_field" type="text" class="form-control" v-model="formDataField">
                <span v-if="getHttpErrors.hasOwnProperty('data_field')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.data_field" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Campos</h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <!-- campo -->
                        <label for="field">Campo</label> <small>(<i class="fas fa-toggle-on"></i> Meta campo?)</small>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <label class="switch s-success mb-0"  data-toggle="tooltip" title="Meta campo">
                                        <input type="checkbox" v-model="metaField">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <input id="field" name="field" type="text" class="form-control" v-model="fieldName">
                        </div>
                        <span v-if="fieldErrors.fieldName != ''" class="invalid-feedback" style="display: block">
                            <strong>{{ fieldErrors.fieldName }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <!-- campo -->
                        <label for="value">Valor</label>
                        <div class="input-group">
                            <input ref="inputSearch" id="value" name="value" type="text" class="form-control" v-model="fieldValue" tabindex="1" :disabled="this.metaField">
                            <div class="input-group-append" data-toggle="modal" data-target="#modalVariables">
                                <button class="btn btn-warning" type="button" data-toggle="tooltip" title="Variáveis" @click="initSearch()" :disabled="this.metaField">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <div class="input-group-append">
                                <button  v-show="!editing" class="btn btn-primary" type="button"  data-toggle="tooltip" title="Adicionar" @click.prevent="addField">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button  v-show="editing" class="btn" type="button"  data-toggle="tooltip" title="Cancelar" @click.prevent="clearFieldsForm">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button  v-show="editing" class="btn btn-primary" type="button"  data-toggle="tooltip" title="Salvar" @click.prevent="updateField">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <span v-if="fieldErrors.fieldValue != ''" class="invalid-feedback" style="display: block">
                            <strong>{{ fieldErrors.fieldValue }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-row mb-0 mt-2" v-for="(field, index) in fields" :key="index">
                    <div class="form-group col-md-6 mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text" :class="{'text-success': field.meta, 'text-secondary': !field.meta, }">
                                    <i class="fas" :class="{'fa-check-square': field.meta, 'fa-square': !field.meta, }"></i>
                                </div>
                            </div>
                            <div class="form-control"><small>{{ field.name }}</small></div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <div class="input-group">
                            <div class="form-control"><small>{{ field.value }}</small></div>
                            <div class="input-group-append">
                                <button class="btn btn-warning btn-sm" type="button" data-toggle="tooltip" title="Editar" @click.prevent="editField(index)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-danger" type="button" @click.prevent="delField(index)" data-toggle="tooltip" title="Remover">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Retorno OK <small>(Condição para validar a chamada a API)</small></h5>
            </div>
            <div class="card-body">
                <div class="form-row mb-0">
                    <div class="form-group col-md-6 mb-0">
                        <!-- campo -->
                        <label for="field_ok">Campo</label>
                        <input id="field_ok" name="field_ok" type="text" class="form-control" v-model="fieldOk">
                        <span v-if="getHttpErrors.hasOwnProperty('field_ok')" class="invalid-feedback" style="display: block">
                            <strong v-for="(error, index) in getHttpErrors.field_ok" :key="index">{{ error }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 mb-0">
                        <!-- campo -->
                        <label for="code_ok">Código de Retorno</label>
                        <div class="input-group">
                            <div class="input-group-prepend" data-toggle="modal" data-target="#modalVariables">
                                <span class="input-group-text"><i class="fas fa-equals"></i></span>
                            </div>
                            <input id="code_ok" name="code_ok" type="text" class="form-control" v-model="codeOk">
                        </div>
                        <span v-if="getHttpErrors.hasOwnProperty('code_ok')" class="invalid-feedback" style="display: block">
                            <strong v-for="(error, index) in getHttpErrors.code_ok" :key="index">{{ error }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/api_endpoints" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeApiEndpoint ">Salvar</button>
        </div>
        <div class="modal fade" id="modalVariables" ref="modalVariables" tabindex="-1" role="dialog" aria-labelledby="modalVariablesLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <input type="text" ref="searchVariableCtrl" name="searchVar" id="searchVar" class="form-control" placeholder="Pesquisar..." v-model="searchVariable">
                    </div>
                    <div class="modal-body">
                        <div style="overflow-y: scroll; height: 400px;">
                            <div class="table-responsive">
                                <table class="table mb-4">
                                    <tbody>
                                        <tr v-for="variable in filteredVariables" :key="variable.id">
                                            <td><span class="badge shadow-none"
                                                style="cursor: pointer; user-select: none"
                                                :class="{'outline-badge-secondary': variable.system_field, 'outline-badge-primary': !variable.system_field}"
                                                @click.prevent="addVariable(variable.field_name)" data-dismiss="modal">{ {{ variable.field_name }} }</span></td>
                                            <td>{{ variable.label }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            editing: false,
            index: null,
            fieldName: '',
            fieldValue: '',
            metaField: false,
            curPos: 0,
            fieldErrors: {
                fieldName: '',
                fieldValue: ''
            }
        }
    },
    mounted() {
        $(this.$refs['modalVariables']).on('shown.bs.modal', () => $(this.$refs['searchVariableCtrl'].focus()))
        this.loadApis()
        this.loadVariables()
        if (this.apiEndpointId) {
            this.loadApiEndpoint(this.apiEndpointId)
        }
    },
    props: {
        apiEndpointId: {
            type: Number,
            default: null
        }
    },
    methods: {
        ...mapActions('endpoints', [
            'loadApis', 'storeApiEndpoint', 'loadApiEndpoint', 'loadVariables'
        ]),
        initSearch() {
            this.searchVariable = ''
            this.curPos = this.$refs['inputSearch'].selectionStart
        },
        addField() {
            if (!this.validateFields()) {
                return
            }

            let field = {
                name: this.fieldName,
                value: this.metaField ? `{ ${this.fieldName} }` : this.fieldValue,
                meta: this.metaField
            }
            this.$store.commit('endpoints/addField', field)

            this.clearFieldsForm()
        },
        editField(id) {
            this.fieldName = this.fields[id].name
            this.fieldValue = this.fields[id].value
            this.metaField = this.fields[id].meta
            this.editing = true
            this.index = id
        },
        updateField() {
            if (!this.validateFields()) {
                return
            }

            let field = {
                name: this.fieldName,
                value: this.metaField ? `{ ${this.fieldName} }` : this.fieldValue,
                meta: this.metaField,
                id: this.index
            }
            this.$store.commit('endpoints/updateField', field)

            this.clearFieldsForm()
        },
        delField(index) {
            this.$store.commit('endpoints/delField', index)
        },
        addVariable(variable) {
            this.fieldValue = '{ '+variable+' }'
        },
        validateFields() {
            this.fieldErrors.fieldName = !this.fieldName ? 'Campo não informado' : ''
            this.fieldErrors.fieldValue = !this.fieldValue && !this.metaField ? 'Valor não informado' : ''

            return this.fieldErrors.fieldName == '' && this.fieldErrors.fieldValue == ''
        },
        clearFieldsForm() {
            this.fieldName = ''
            this.fieldValue = ''
            this.metaField = false
            this.index = null
            this.editing = false
            this.fieldErrors = {
                fieldName: '',
                fieldValue: ''
            }
        }
    },
    computed: {
        ...mapGetters('endpoints', [
            'getHttpErrors', 'filteredVariables'
        ]),
        apis() {
            return this.$store.state.endpoints.apis
        },
        httpMethods() {
            return this.$store.state.endpoints.httpMethods
        },
        formBodys() {
            return this.$store.state.endpoints.formBodys
        },
        fields() {
            return this.$store.state.endpoints.fields
        },
        searchVariable: {
            get() {
                return this.$store.state.endpoints.searchVariable
            },
            set(value) {
                this.$store.commit('endpoints/setSearchVariable', value)
            }
        },
        name: {
            get() {
                return this.$store.state.endpoints.name
            },
            set(value) {
                this.$store.commit('endpoints/setName', value)
            }
        },
        apiId: {
            get() {
                return this.$store.state.endpoints.apiId
            },
            set(value) {
                this.$store.commit('endpoints/setApiId', value)
            }
        },
        relativeUrl: {
            get() {
                return this.$store.state.endpoints.relativeUrl
            },
            set(value) {
                this.$store.commit('endpoints/setRelativeUrl', value)
            }
        },
        httpMethod: {
            get() {
                return this.$store.state.endpoints.httpMethod
            },
            set(value) {
                this.$store.commit('endpoints/setHttpMethod', value)
            }
        },
        formBody: {
            get() {
                return this.$store.state.endpoints.formBody
            },
            set(value) {
                this.$store.commit('endpoints/setFormBody', value)
            }
        },
        formDataField: {
            get() {
                return this.$store.state.endpoints.formDataField
            },
            set(value) {
                this.$store.commit('endpoints/setFormDataField', value)
            }
        },
        fieldOk: {
            get() {
                return this.$store.state.endpoints.fieldOk
            },
            set(value) {
                this.$store.commit('endpoints/setFieldOk', value)
            }
        },
        codeOk: {
            get() {
                return this.$store.state.endpoints.codeOk
            },
            set(value) {
                this.$store.commit('endpoints/setCodeOk', value)
            }
        }
    }
}
</script>
