<template>
    <div>
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
            <div class="form-group col-md-10">
                <label for="name">Ação</label>
                <div class="input-group">
                    <input type="text" name="name" id="name" class="form-control" v-model="name">
                </div>
                <span v-if="getHttpErrors.name" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <!-- Produto -->
                <label for="product">Produto</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="productLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="product" class="form-control" v-model="productId">
                        <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                    </select>
                </div>
                <span v-if="getHttpErrors.product_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.product_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
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
                <span v-if="getHttpErrors.event_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.event_id" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4" v-if="selectedEvent && selectedEvent.system_event">
                <!-- Trigger data -->
                <label for="trigger_data">Condição Evento do Sistema</label>
                <input type="text" name="trigger_data" id="trigger_data" class="form-control" v-model="triggerData">
                <span v-if="getHttpErrors.trigger_data" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.trigger_data" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group" :class="{'col-md-4': selectedEvent && selectedEvent.system_event, 'col-md-6': !selectedEvent || !selectedEvent.system_event}">
                <!-- Trigger data -->
                <label for="delay">Atraso</label>
                <input type="text" name="delay" id="delay" class="form-control" v-model="delay">
                <span v-if="getHttpErrors.delay" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.delay" :key="index">{{ error }}</strong>
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
                <label for="api">API</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="apiLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="api" class="form-control" v-model="apiId">
                        <option v-for="api in apis" :key="api.id" :value="api.id">{{ api.name }}</option>
                    </select>
                </div>
                <span v-if="getHttpErrors.api_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.api_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <!-- Endpoint -->
                <label for="endpoint">Endpoint</label>
                <div class="input-group">
                    <div class="input-group-prepend" v-if="endpointLoading">
                        <span class="input-group-text" id="basic-addon5">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </div>
                    <select id="endpoint" class="form-control" v-model="endpointId">
                        <option v-for="endpoint in endpoints" :key="endpoint.id" :value="endpoint.id">{{ endpoint.name }}</option>
                    </select>
                </div>
                <span v-if="getHttpErrors.endpoint_id" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.endpoint_id" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="card" v-if="getMetaFields && getMetaFields.length > 0">
            <div class="card-header">
                <h5 class="mb-0">Dados da Ação <small class="float-right"><i class="fas fa-exclamation-circle text-danger"></i> ATENÇÃO! Os campos abaixo são totalmente isentos de validação.</small></h5>
            </div>
            <div class="card-body">
                <div class="form-row" v-for="metaField in getMetaFields" :key="metaField.name">
                    <div class="form-group col">
                        <!-- Meta Field -->
                        <label :for="metaField.name">{{ metaField.name }}</label>
                        <textarea :ref="metaField.name" :name="metaField.name" :id="metaField.name" cols="30" rows="2" class="form-control" :value="actionData[metaField.name]" @input="setActionData({name: metaField.name, value: $event.target.value})"></textarea>
                        <button class="badge outline-badge-warning float-right mt-1 ml-2" data-toggle="tooltip" title="Variáveis" @click="initVariablesSearch(metaField.name)"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-0">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/actions" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeAction ">Salvar</button>
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
            messagePosition: 0,
            inputMeta: null,
            insertedVariable: '',
            currentRef: '',
            dataFields: {},
        }
    },
    props: {
        actionId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        $(this.$refs['modalVariables']).on('hidden.bs.modal', () => {
            this.restoreMessageFocus()
        })
        $(this.$refs['modalVariables']).on('shown.bs.modal', () => $(this.$refs['searchVariableCtrl'].focus()))

        this.loadProducts()
        this.loadApis()
        this.loadVariables()
        if (this.actionId) {
            this.loadAction(this.actionId)
            this.$store.commit('actions/setActionId', this.actionId)
        }
    },
    methods: {
        setActionData(e) {
            this.$store.commit('actions/setActionData', e)
        },
        ...mapActions('actions', [
            'loadAction', 'loadProducts', 'loadApis', 'loadVariables', 'storeAction'
        ]),
        addVariable(variable) {
            let v = `{ ${variable} } `
            this.messagePosition = this.inputMeta.selectionStart
            let txt = this.actionData[this.currentRef]

            let obj = {
                name: this.currentRef,
                value:  txt == undefined ? v : `${txt.slice(0,this.messagePosition)}${v}${txt.slice(this.messagePosition)}`
            }

            this.$store.commit('actions/setActionData', obj)
            this.$forceUpdate()
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
        ...mapGetters('actions', [
            'getHttpErrors', 'selectedEvent', 'filteredVariables', 'getMetaFields'
        ]),
        actionData() {
            return this.$store.state.actions.actionData
        },
        productLoading() {
            return this.$store.state.actions.productLoading
        },
        eventLoading() {
            return this.$store.state.actions.eventLoading
        },
        apiLoading() {
            return this.$store.state.actions.apiLoading
        },
        endpointLoading() {
            return this.$store.state.actions.endpointLoading
        },
        products() {
            return this.$store.state.actions.products
        },
        events() {
            return this.$store.state.actions.events
        },
        apis() {
            return this.$store.state.actions.apis
        },
        endpoints() {
            return this.$store.state.actions.endpoints
        },
        delayTypes() {
            return this.$store.state.actions.delayTypes
        },
        name: {
            get() {
                return this.$store.state.actions.name
            },
            set(value) {
                this.$store.commit('actions/setName', value)
            }
        },
        productId: {
            get() {
                return this.$store.state.actions.productId
            },
            set(value) {
                this.$store.dispatch('actions/selectProduct', value)
            }
        },
        eventId: {
            get() {
                return this.$store.state.actions.eventId
            },
            set(value) {
                this.$store.commit('actions/setEventId', value)
            }
        },
        apiId: {
            get() {
                return this.$store.state.actions.apiId
            },
            set(value) {
                this.$store.dispatch('actions/selectApi', value)
            }
        },
        endpointId: {
            get() {
                return this.$store.state.actions.endpointId
            },
            set(value) {
                this.$store.dispatch('actions/selectEndpoint', value)
            }
        },
        delay: {
            get() {
                return this.$store.state.actions.delay
            },
            set(value) {
                this.$store.commit('actions/setDelay', value)
            }
        },
        delayType: {
            get() {
                return this.$store.state.actions.delayType
            },
            set(value) {
                this.$store.commit('actions/setDelayType', value)
            }
        },
        active: {
            get() {
                return this.$store.state.actions.active
            },
            set(value) {
                this.$store.commit('actions/setActive', value)
            }
        },
        triggerData: {
            get() {
                return this.$store.state.actions.triggerData
            },
            set(value) {
                this.$store.commit('actions/setTriggerData', value)
            }
        },
        searchVariable: {
            get() {
                return this.$store.state.actions.searchVariable
            },
            set(value) {
                this.$store.commit('actions/setSearchVariable', value)
            }
        }
    }
}
</script>
