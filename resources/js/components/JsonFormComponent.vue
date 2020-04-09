<template>
    <div>
        <!-- result json mapped fileds -->
        <input type="hidden" name="json_mapping" :value="getFinalFieldMapping">

        <!-- common properties of the webhook -->
        <div class="form-row">
            <div class="form-group col-md-4">
                <!-- name -->
                <label for="name">Nome</label>
                <input id="name" name="name" type="text" class="form-control" v-model="name">
                <span v-if="httpErrors.hasOwnProperty('name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-8">
                <!-- relative_url -->
                <label for="relative_url">URL</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="relative_url">{{ url }}</span>
                    </div>
                    <input type="text" class="form-control" id="relative_url" name="relative_url" aria-describedby="basic-addon3" placeholder="" v-model="relative_url">
                </div>
                <span v-if="httpErrors.hasOwnProperty('relative_url')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.relative_url" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <!-- token -->
                <label for="token">Token</label>
                <input id="token" name="token" type="text" class="form-control" v-model="token" placeholder="">
                <span v-if="httpErrors.hasOwnProperty('token')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.token" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <!-- source json -->
                <label for="json_text">JSON</label>
                <textarea class="form-control" rows="4" id="json" name="json" v-model="jsonSchema"></textarea>
                <span v-if="httpErrors.hasOwnProperty('json')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.json" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Mapeamento de Dados</h5>
            </div>
            <div class="card-body">
                <div class="form-row mb-3">
                    <div class="col-md-4">
                        <!-- campos locais -->
                        <label for="local_field">Campo Local</label>
                        <select id="local_field" class="form-control" v-model="localFieldName">
                            <option v-for="field in localFields" :key="field.field_name" :value="field.field_name">{{ field.label }}</option>
                        </select>
                        <span v-if="errors.localField != ''" class="invalid-feedback" style="display: block">
                            <strong>{{ errors.localField }}</strong>
                        </span>
                    </div>
                    <div class="col-md-3">
                        <!-- functions -->
                        <label for="data_func">Modificador</label>
                        <select id="data_func" class="form-control" v-model="localFunction">
                            <option value="">Nenhum</option>
                            <option value="firstName">Primeiro Nome</option>
                            <option value="shortURL">URL Encurtada</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <!-- campos remotos -->
                        <label for="json_field">Campo Webhook</label>

                        <div class="input-group">
                            <select id="json_field" class="form-control" v-model="remoteFieldName">
                                <option v-for="(item, index) in remoteFields" :key="index" :value="item.field">{{ item.field }}</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" @click.prevent="addNewMapping">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <span v-if="errors.remoteField != ''" class="invalid-feedback" style="display: block">
                            <strong>{{ errors.remoteField }}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-row mt-1" v-for="item in mappings" :key="item.localField.field_name">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="form-control"><small>{{ item.localField.label }}</small></div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon5">
                                    <i class="fas fa-equals"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon5">
                                    <i class="fas fa-code-branch"></i>
                                </span>
                            </div>
                            <div class="form-control"><small>{{ item.function ? item.function : 'Nenhum' }}</small></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon5">
                                    <i class="fas fa-code"></i>
                                </span>
                            </div>
                            <div class="form-control"><small>{{ item.remoteField }}</small></div>
                            <div class="input-group-append">
                                <button class="btn btn-danger" @click="deleteFieldMapping(item)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <span v-if="httpErrors.hasOwnProperty('json_mapping')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.json" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/webhooks" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeWebhook">Salvar</button>
        </div>
    </div>
</template>

<script>

import {mapGetters, mapActions} from 'vuex'

export default {
    data() {
        return {
            localFieldName: '',
            remoteFieldName: '',
            localFunction: '',
            errors: {
                localField: '',
                remoteField: ''
            }
        }
    },
    computed: {
        ...mapGetters('webhooks', {
            localFields: 'getLocalNotMappedFields',
            remoteFields: 'getRemoteFields',
            mappings: 'getMappings',
            getLocalFieldByName: 'getLocalFieldByName',
            getFinalFieldMapping: 'getFinalFieldMapping',
            httpErrors: 'getHttpErrors'
        }),
        name: {
            get() {
                return this.$store.state.webhooks.name
            },
            set(value) {
                this.$store.commit('webhooks/setName', value)
            }
        },
        relative_url: {
            get() {
                return this.$store.state.webhooks.relative_url
            },
            set(value) {
                this.$store.commit('webhooks/setRelativeUrl', value)
            }
        },
        token: {
            get() {
                return this.$store.state.webhooks.token
            },
            set(value) {
                this.$store.commit('webhooks/setToken', value)
            }
        },
        jsonSchema: {
            get() {
                return this.$store.state.webhooks.jsonSchema
            },
            set(value) {
                this.extractJsonFields(value)
            }
        },
    },
    props: {
        url: {
            type: String,
            required: true
        },
        webhook: {
            type: String,
            required: false
        }
    },
    mounted() {
        this.getLocalFields()
        if (this.webhook) {
            this.loadWebhook(this.webhook)
        }
    },
    methods: {
        ...mapActions('webhooks', {
            extractJsonFields: 'extractJsonFields',
            createFieldMapping: 'createFieldMapping',
            deleteFieldMapping: 'deleteFieldMapping',
            storeWebhook: 'storeWebhook',
            getLocalFields: 'getLocalFields',
            loadWebhook: 'loadWebhook'
        }),
        addNewMapping() {
            let mapping = {
                localField: this.getLocalFieldByName(this.localFieldName),
                function: this.localFunction,
                remoteField: this.remoteFieldName
            }
            this.clearValidation()

            if (this.validateMapping()) {
                this.createFieldMapping(mapping)
                this.clearForm()
            }

        },
        validateMapping() {
            let valid = true

            if (!this.localFieldName) {
                valid = false
                this.errors.localField = 'Campo Local não informado'
            }

            if (!this.remoteFieldName) {
                valid = false
                this.errors.remoteField = 'Campo Webhook não informado'
            }

            return valid
        },
        clearForm() {
            this.localFieldName = '',
            this.localFunction = '',
            this.remoteFieldName = ''
        },
        clearValidation() {
            this.errors = {
                localField: '',
                remoteField: ''
            }
        }
    }
}
</script>
