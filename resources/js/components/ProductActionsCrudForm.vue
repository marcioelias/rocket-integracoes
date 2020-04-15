<template>
    <div class="p-3">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="field">Ativo</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <label class="switch s-success mb-0"  data-toggle="tooltip" title="Ativo/Inativo">
                                <input type="checkbox" v-model="active">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control">{{ active ? 'Sim' : 'Não' }}</div>
                </div>
            </div>
            <div class="form-group col-md-5">
                <!-- Evento -->
                <label for="event">Evento</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="eventLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="event" class="form-control" v-model="eventId">
                        <option v-for="event in events" :key="event.id" :value="event.id">{{ event.name }}</option>
                    </select>
                </div>
                <span v-if="validationErrors.event_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.event_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-5">
                <label for="name">Ação</label>
                <div class="input-group">
                    <input type="text" name="name" id="name" class="form-control" v-model="name">
                </div>
                <span v-if="validationErrors.action_name" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.action_name" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4" v-if="selectedEvent && selectedEvent.system_event">
                <!-- Trigger data -->
                <label for="trigger_data">Condição Evento do Sistema</label>
                <input type="text" name="trigger_data" id="trigger_data" class="form-control" v-model="triggerData">
                <span v-if="validationErrors.trigger_data" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.trigger_data" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group" :class="{'col-md-4': selectedEvent && selectedEvent.system_event, 'col-md-6': !selectedEvent || !selectedEvent.system_event}">
                <!-- Trigger data -->
                <label for="delay">Atraso</label>
                <input type="text" name="delay" id="delay" class="form-control" v-model="delay">
                <span v-if="validationErrors.delay" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.delay" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group" :class="{'col-md-4': selectedEvent && selectedEvent.system_event, 'col-md-6': !selectedEvent || !selectedEvent.system_event}">
                <!-- Trigger data -->
                <label for="delay_type">Unidade de Atraso</label>
                <select id="delay_type" class="form-control" v-model="delayType">
                    <option v-for="delayType in delayTypes" :key="delayType.type" :value="delayType.type">{{ delayType.label }}</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <!-- API -->
                <label for="api_id">API</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="apiLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="api_id" class="form-control" v-model="apiId">
                        <option v-for="api in apis" :key="api.id" :value="api.id">{{ api.name }}</option>
                    </select>
                </div>
                <span v-if="validationErrors.api_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.api_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <!-- Endpoint -->
                <label for="api_endpoint_id">Endpoint</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="endpointLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="api_endpoint_id" class="form-control" v-model="apiEndpointId">
                        <option v-for="endpoint in api_endpoints" :key="endpoint.id" :value="endpoint.id">{{ endpoint.name }}</option>
                    </select>
                </div>
                <span v-if="validationErrors.api_endpoint_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in validationErrors.api_endpoint_id" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in" apper>
            <div class="card" v-if="metaFields && metaFields.length > 0">
                <div class="card-header">
                    <h5 class="mb-0">Dados da Ação <small class="float-right"><i class="fas fa-exclamation-circle text-danger"></i> ATENÇÃO! Os campos abaixo são totalmente isentos de validação.</small></h5>
                </div>
                <div class="card-body">
                    <div class="form-row" v-for="metaField in metaFields" :key="metaField.name">
                        <div class="form-group col">
                            <!-- Meta Field -->
                            <label :for="metaField.name">{{ metaField.name }}</label>
                            <textarea :ref="metaField.name" :name="metaField.name" :id="metaField.name" cols="30" rows="2" class="form-control" :value="actionData[metaField.name]" @input="setActionData({name: metaField.name, value: $event.target.value})"></textarea>
                            <button class="badge outline-badge-warning float-right mt-1 ml-2" data-toggle="tooltip" title="Variáveis" @click="initVariablesSearch(metaField.name)"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <div class="mt-0 mb-3 float-right">
            <button class="btn mt-3 mr-3" @click="$emit('onCancel')">Cancelar</button>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="addAction">Salvar</button>
        </div>
        <div class="modal fade" ref="modalVariables" id="modalVariables" tabindex="-1" role="dialog" aria-labelledby="modalVariablesLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <input type="text" name="searchVar" ref="searchVariableCtrl" id="searchVar" class="form-control" placeholder="Pesquisar..." v-model="searchVariable">
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
            id: null,
            name: '',
            event_id: '',
            api_endpoint_id: '',
            delay: 0,
            delayType: 'minute',
            triggerData: '',
            active: true,
            webhooks: [],
            api_id: null,
            api_endpoints: [],
            eventLoading: false,
            apiLoading: false,
            endpointLoading: false,
            selectedEvent: {},
            selectedEndpoint: {},
            searchVariable: '',
            validationErrors: {},
            metaFields: [],
            actionData: {},
            searchVariable: '',
            inputMeta: null,
            currentRef: '',
            messagePosition: 0,
            insertedVariable: '',
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
        }
    },
    props: {
        actionIdx: {
            type: Number,
            default: null
        },
        isEditing: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        $(this.$refs['modalVariables']).on('hidden.bs.modal', () => {
            this.restoreMessageFocus()
        })
        $(this.$refs['modalVariables']).on('shown.bs.modal', () => $(this.$refs['searchVariableCtrl'].focus()))

        if (this.isEditing) {
            this.loadAction()
        }
    },
    methods: {
        loadAction() {
            let action = this.$store.state.products.actions[this.actionIdx]
            this.id = action.id
            this.name = action.name
            this.eventId = action.event_id
            this.delay = action.delay
            this.delayType = action.delay_type
            this.triggerData = action.trigger_data
            this.active = action.active
            this.apiId = action.api_endpoint.api_id
            this.apiEndpointId = action.api_endpoint.id
            this.actionData = JSON.parse(action.data)
        },
        setActionData(obj) {
            this.$set(this.actionData, obj.name, obj.value)
        },
        async loadEndpoints(api) {
            this.endpointLoading = true
            await axios.get('/json/api_endpoints/api/'+api)
                .then(r => {
                    this.api_endpoints = r.data
                    this.selectedEndpoint = this.api_endpoints.find(e => e.id == this.api_endpoint_id)
                    let ep = this.selectedEndpoint
                    let m = ep ? JSON.parse(ep.json) : []
                    this.metaFields = m.filter(f => (f.meta))
                    this.endpointLoading = false
                })
        },
        validateAction() {
            let errors = {}
            let valid = true
            if (!this.name) {
                errors.action_name = 'Campo Ação não informado'
                valid = false
            }
            if (!this.event_id) {
                errors.event_id = 'Campo Evento não informado'
                valid = false
            }
            if (!this.api_id) {
                errors.api_id = 'Campo API não informado'
                valid = false
            }
            if (!this.api_endpoint_id) {
                errors.api_endpoint_id = 'Campo Endpoint não informado'
                valid = false
            }
            if (this.data === {}) {
                errors.data = 'Nenhuma Condição adicionada'
                valid = false
            }
            this.validationErrors = errors
            return valid
        },
        addAction() {
            if (!this.validateAction()) {
                swal({
                    title: 'Ooops!',
                    text: 'Algo deu errado.',
                    type: 'error',
                    confirmButtonText: 'Ok',
                    padding: '2em'
                })
            } else {
                self = this
                let action = {
                    id: this.id,
                    name: this.name,
                    event_id: this.event_id,
                    api_endpoint_id: this.api_endpoint_id,
                    delay: this.delay,
                    delay_type: this.delayType,
                    data: JSON.stringify(this.actionData),
                    trigger_data: this.triggerData,
                    active: this.active,
                    api_id: this.api_id,
                    api_endpoint: this.selectedEndpoint,
                    event: this.selectedEvent
                }
                if (this.isEditing) {
                    this.$store.commit('products/updateAction', {actionIdx: this.actionIdx, action: action} )
                } else {
                    this.$store.commit('products/addAction', action)
                }
                let op = (this.isEditing) ? 'Altadada!' : 'Incluída!'
                swal({
                    title: 'Sucesso!',
                    text: `${action.name} foi ${op}`,
                    type: 'success',
                    confirmButtonText: 'Ok',
                    padding: '2em'
                }).then(function(result) {
                    self.$emit('onAddAction')
                })
            }
        },
        clearData() {
            this.name = ''
            this.eventId = null
            this.delay = 0
            this.delayType = 'minute'
            this.triggerData = ''
            this.active = true
            this.apiId = null
            this.apiEndpointId = null
            this.actionData = {}
        },
        addVariable(variable) {
            let v = `{ ${variable} } `
            this.messagePosition = this.inputMeta.selectionStart
            let txt = this.actionData[this.currentRef]

            let obj = {
                name: this.currentRef,
                value:  txt == undefined ? v : `${txt.slice(0,this.messagePosition)}${v}${txt.slice(this.messagePosition)}`
            }

            this.setActionData(obj)
            this.insertedVariable = v
        },
        restoreMessageFocus() {
            this.inputMeta.selectionStart = this.messagePosition + this.insertedVariable.length
            this.inputMeta.selectionEnd = this.inputMeta.selectionStart
            this.$nextTick(() => $(this.inputMeta).focus())
        },
        initVariablesSearch(refComp) {
            this.currentRef = refComp
            this.inputMeta = (this.$refs[refComp][0]) ? this.$refs[refComp][0] : null

            this.searchVariable = ''
            $(this.$refs.modalVariables).modal('show')
        }
    },
    computed: {
        eventId: {
            get() {
                return this.event_id
            },
            set(value) {
                this.event_id = value
                this.selectedEvent = this.events.find(e => e.id == this.event_id) ?? {}
            }
        },
        filteredVariables() {
            if (this.searchVariable.length >= 2) {
                return this.variables.filter(v => v.field_name.toLowerCase().search(this.searchVariable.toLowerCase()) >= 0 || v.label.toLowerCase().search(this.searchVariable.toLowerCase()) >= 0)
            } else {
                return this.variables
            }
        },
        webhookId() {
            return this.$store.state.products.webhookId
        },
        events() {
            return this.$store.state.products.events
        },
        apis() {
            return this.$store.state.products.apis
        },
        variables() {
            return this.$store.state.products.variables
        },
        apiId: {
            get: function () {
                return this.api_id
            },
            set: function (value) {
                this.api_id = value
                this.loadEndpoints(value)
            }
        },
        apiEndpointId: {
            get: function () {
                return this.api_endpoint_id
            },
            set: function (value) {
                this.api_endpoint_id = value
                this.selectedEndpoint = this.api_endpoints.find(e => e.id == this.api_endpoint_id)
                let ep = this.selectedEndpoint
                let m = ep ? JSON.parse(ep.json) : []
                this.metaFields = m.filter(f => (f.meta))
                this.endpointLoading = false
            }
        },
    }
}
</script>
