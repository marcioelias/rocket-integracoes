<template>
    <div>
        <div class="form-row">
            <div class="form-group col">
                <label for="short_domain">Dominio encurtado</label>
                <input type="text" name="short_domain" id="short_domain" class="form-control" v-model="shortDomain">
                <span v-show="httpErrors.hasOwnProperty('short_domain')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.short_domain" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="short_url_api">Tipo de Encurtador</label>
                <select name="short_url_api" id="short_url_api" class="form-control" v-model="shortUrlApi">
                    <option v-for="api in shortUrlApis" :key="api.value" :value="api.value">{{ api.label }}</option>
                </select>
                <span v-show="httpErrors.hasOwnProperty('short_url_api')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.short_url_api" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="api_key">API Key</label>
                <input type="text" name="api_key" id="api_key" class="form-control" v-model="apiKey">
                <span v-show="httpErrors.hasOwnProperty('api_key')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in httpErrors.api_key" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeShortUrlConfig">Salvar</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            shortUrlConfigId: null,
            shortDomain: '',
            shortUrlApi: '',
            apiKey: '',
            httpErrors: {},
            shortUrlApis: [
                {
                    label: 'Local',
                    value: 'local'
                },
                {
                    label: 'Short.cm',
                    value: 'short_cm'
                }
            ]
        }
    },
    props: {
        model: {
            type: Object,
            required: true
        }
    },
    mounted() {
        if (this.model) {
            this.shortUrlConfigId = this.model.id
            this.shortDomain = this.model.short_domain
            this.shortUrlApi = this.model.short_url_api
            this.apiKey = this.model.api_key
        }
    },
    methods: {
        async storeShortUrlConfig() {
            this.httpErrors = {}
            let self = this
            let shortUrlConfig = {
                id: this.shortUrlConfigId,
                short_domain: this.shortDomain,
                short_url_api: this.shortUrlApi,
                api_key: this.apiKey
            }


            await axios.put('/short_url_configs/'+this.shortUrlConfigId , shortUrlConfig)
                .then(r => {
                    if (r.status === 200) {
                        swal({
                            title: 'Sucesso!',
                            text: 'Registro alterado.',
                            type: 'success',
                            confirmButtonText: 'Ok',
                            padding: '2em'
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
                                self.httpErrors = e.response.data.errors
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
}
</script>
