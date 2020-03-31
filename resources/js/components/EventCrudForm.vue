<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <!-- name -->
                <label for="name">Webhook</label>
                <select id="json_field" class="form-control" v-model="webhookId">
                    <option v-for="wh in getWebhooks" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <!-- name -->
                <label for="name">Evento</label>
                <input id="name" name="name" type="text" class="form-control" v-model="name">
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
                        <label for="name">Agrupar</label>
                        <select id="json_field" class="form-control" v-model="logicOperation">
                            <option v-for="lp in logicOperations" :key="lp.value" :value="lp">{{ lp.label }}</option>
                        </select>
                    </div>
                    <div class="form-group" :class="{'col-md-4': conditions.length > 0, 'col-md-6': conditions.length == 0}">
                        <!-- field -->
                        <label for="json_field">Campo</label>

                        <div class="input-group">
                            <select id="json_field" class="form-control" v-model="field">
                                <option v-for="(item, index) in remoteFields" :key="index" :value="item.field">{{ item.field }}</option>
                            </select>
                        </div>
                        <!-- <span v-if="errors.remoteField != ''" class="invalid-feedback" style="display: block">
                            <strong>{{ errors.remoteField }}</strong>
                        </span> -->
                    </div>
                    <div class="form-group col-md-2">
                        <!-- operation -->
                        <label for="name">Operação</label>
                        <select id="json_field" class="form-control" v-model="operation">
                                <option v-for="op in opterations" :key="op.value" :value="op">{{ op.label }}</option>
                            </select>
                    </div>
                    <div class="form-group col-md-4">
                        <!-- operation -->
                        <label for="name">Valor</label>
                        <div class="input-group">
                            <input id="name" name="name" type="text" class="form-control" v-model="value">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" @click.prevent="addCondition">
                                    <i class="fas fa-plus"></i>
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
                                <button class="btn btn-danger" type="button" @click.prevent="delCondition(index)">
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
            field: '',
            operation: '',
            value: '',
            logicOperation: '',
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
        this.loadWebhooks()
        if (this.eventId) {
            //load the current event
        }
    },
    methods: {
        ...mapActions('events', ['loadWebhooks', 'storeEvent']),
        addCondition() {
            let condition = {
                field: this.field,
                operation: this.operation,
                value: this.value,
                logic: this.logicOperation
            };

            this.$store.commit('events/addCondition', condition)
        },
        delCondition(id) {
            console.log('apaganod: '+id)
            this.$store.commit('events/delCondition', id)
        }
    },
    computed: {
        ...mapGetters('events', ['getWebhooks', 'selectWebhook']),
        remoteFields() {
            return this.$store.state.events.remoteFields
        },
        conditions() {
            return this.$store.state.events.conditions
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
