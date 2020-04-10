<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <!-- webhook -->
                <label for="webhook_id">Webhook</label>
                <select id="webhook_id" name="webhook_id" class="form-control" v-model="webhookId">
                    <option v-for="wh in getWebhooks" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
                </select>
                <span v-show="getHttpErrors.hasOwnProperty('webhook_id')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.webhook_id" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <!-- name -->
                <label for="name">Evento</label>
                <input id="name" name="name" type="text" class="form-control" v-model="name">
                <span v-show="getHttpErrors.hasOwnProperty('name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <!-- trigger system events -->
                <label for="trigger_system_event">Disparar Evento Interno</label>
                <select id="trigger_system_event" name="trigger_system_event" class="form-control" v-model="triggerSystemEvent">
                    <option v-for="ste in triggerSystemEvents" :key="ste.value" :value="ste.value">{{ ste.label }}</option>
                </select>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Condições</h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-2" v-if="conditions.length > 0">
                        <!-- value -->
                        <label for="group_condition">Agrupar</label>
                        <select id="group_condition" name="group_condition" class="form-control" v-model="logicOperation">
                            <option v-for="lp in logicOperations" :key="lp.value" :value="lp">{{ lp.label }}</option>
                        </select>
                        <span v-show="conditionErrors.logic" class="invalid-feedback" style="display: block">
                            <strong>{{ conditionErrors.logic }}</strong>
                        </span>
                    </div>
                    <div class="form-group" :class="{'col-md-4': conditions.length > 0, 'col-md-6': conditions.length == 0}">
                        <!-- field -->
                        <label for="field">Campo</label>

                        <div class="input-group">
                            <select id="field" name="field" class="form-control" v-model="field">
                                <option v-for="(item, index) in remoteFields" :key="index" :value="item.field">{{ item.field }}</option>
                            </select>
                        </div>
                        <span v-show="conditionErrors.field" class="invalid-feedback" style="display: block">
                            <strong>{{ conditionErrors.field }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-2">
                        <!-- operation -->
                        <label for="operation">Operação</label>
                        <select id="operation" name="operation" class="form-control" v-model="operation">
                            <option v-for="op in opterations" :key="op.value" :value="op">{{ op.label }}</option>
                        </select>
                        <span v-show="conditionErrors.operation" class="invalid-feedback" style="display: block">
                            <strong>{{ conditionErrors.operation }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-4">
                        <!-- operation -->
                        <label for="value">Valor</label>
                        <div class="input-group">
                            <input id="value" name="value" type="text" class="form-control" v-model="value">
                            <div class="input-group-append">
                                <button  v-show="!editing" class="btn btn-primary" type="button"  data-toggle="tooltip" title="Adicionar" @click.prevent="addCondition">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button  v-show="editing" class="btn" type="button"  data-toggle="tooltip" title="Cancelar" @click.prevent="clearEventConditionForm">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button  v-show="editing" class="btn btn-primary" type="button"  data-toggle="tooltip" title="Salvar" @click.prevent="updateCondition">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- mostrar condições cadastradas -->
                <div class="form-row" v-for="(item, index) in conditions" :key="index">
                    <div class="col-md-2 p-1"><div class="form-control">{{ item.logic.label }}</div></div>
                    <div class="col-md-4 p-1"><div class="form-control">{{ item.field }}</div></div>
                    <div class="col-md-2 p-1"><div class="form-control">{{ item.operation.label }}</div></div>
                    <div class="col-md-4 p-1">
                        <div class="input-group">
                            <div class="form-control">{{ item.value }}</div>
                            <div class="input-group-append">
                                <button class="btn btn-warning btn-sm" type="button" data-toggle="tooltip" title="Editar" @click.prevent="editCondition(index)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-danger btn-sm" type="button"  data-toggle="tooltip" title="Remover" @click.prevent="delCondition(index)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/events" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeEvent">Salvar</button>
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
            field: '',
            operation: '',
            value: '',
            logicOperation: '',
            conditionErrors: {
                logic: false,
                field: false,
                operation: false,
                value: false
            },
            opterations: [
                {
                    label: 'Igual',
                    value: 'equal'
                },
                {
                    label: 'Diferente',
                    value: 'not-equal'
                },
                {
                    label: 'Maior',
                    value: 'greater-then'
                },
                {
                    label: 'Maior ou Igual',
                    value: 'greater-or-equal'
                },
                {
                    label: 'Menor',
                    value: 'less-then'
                },
                {
                    label: 'Menor ou Igual',
                    value: 'less-or-equal'
                }
            ],
            logicOperations: [
                {
                    label: 'E',
                    value: 'and'
                },
                {
                    label: 'Ou',
                    value: 'or'
                }
            ],
            webhookEvents: [],
        }
    },
    props: {
        eventId: {
            type: Number,
            required: false,
            default: null
        }
    },
    mounted() {
        //this.loadWebhook(this.webhookId)
        if (this.eventId) {
            this.$store.commit('events/setEventId', this.eventId)
        }
        this.loadWebhooks()
    },
    methods: {
        ...mapActions('events', ['loadWebhooks', 'storeEvent', 'loadEvent']),
        addCondition() {
            if (!this.validate) {
                return
            }
            let condition = {
                field: this.field,
                operation: this.operation,
                value: this.value,
                logic: this.logicOperation
            };

            this.$store.commit('events/addCondition', condition)

            this.clearEventConditionForm()
        },
        updateCondition() {
            if (!this.validate) {
                return
            }
            let condition = {
                field: this.field,
                operation: this.operation,
                value: this.value,
                logic: this.logicOperation,
                id: this.index
            };

            this.$store.commit('events/updateCondition', condition)
            this.clearEventConditionForm()
        },
        delCondition(id) {
            this.$store.commit('events/delCondition', id)
        },
        editCondition(id) {
            let data = this.conditions[id]
            this.logicOperation = data.logic
            this.field = data.field
            this.operation = data.operation
            this.value = data.value
            this.editing = true
            this.index = id
        },
        clearEventConditionForm() {
            this.logicOperation = ''
            this.field = ''
            this.operation = ''
            this.value = ''
            this.index = null
            this.editing = false,
            this.conditionErrors =  {
                logic: false,
                field: false,
                operation: false,
                value: false
            }
        },
        validate() {
            let hasErrors = false;
            if (this.editing) {
                if (this.index > 0 && !this.logic) {
                    this.conditionErrors.logic = 'Regra de agregação não informada'
                    hasErrors = true
                }
            }
            if (!this.field) {
                this.conditionErrors.field = 'Campo não informado'
                hasErrors = true
            }
            if (!this.operation) {
                this.conditionErrors.opterations = 'Operador não informado'
                hasErrors = true
            }
            if (!this.value) {
                this.conditionErrors.value = 'Valor não informado'
                hasErrors = true
            }

            return hasErrors
        }
    },
    computed: {
        ...mapGetters('events', ['getWebhooks', 'selectWebhook', 'getHttpErrors']),
        remoteFields() {
            return this.$store.state.events.remoteFields
        },
        conditions() {
            return this.$store.state.events.conditions
        },
        triggerSystemEvents() {
            return this.$store.state.events.triggerSystemEvents
        },
        triggerSystemEvent: {
            get() {
                return this.$store.state.events.triggerSystemEvent
            },
            set(value) {
                this.$store.commit('events/setTriggerSystemEvent', value)
            }
        },
        webhookId: {
            get() {
                return this.$store.state.events.webhookId
            },
            set(value) {
                this.$store.dispatch('events/selectWebhook', value);
            }
        },
        name: {
            get() {
                return this.$store.state.events.name
            },
            set(value) {
                this.$store.commit('events/setName', value)
            }
        }
    }
}
</script>
