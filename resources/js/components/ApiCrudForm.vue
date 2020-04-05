<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Nome</label>
                <input type="text" class="form-control" name="name" id="name" v-model="name">
                <span v-show="getHttpErrors.hasOwnProperty('name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-8">
                <label for="base_url">Base URL</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span>https://</span>
                        </div>
                    </div>
                    <input type="text" class="form-control" name="base_url" id="base_url" v-model="baseUrl">
                </div>
                <span v-show="getHttpErrors.hasOwnProperty('base_url')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.base_url" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="authentication">Autenticação</label>
                <select id="authentication" class="form-control" v-model="authentication">
                    <option v-for="auth in authentications" :key="auth.name" :value="auth.name">{{ auth.label }}</option>
                </select>
                <span v-if="getHttpErrors.hasOwnProperty('authentication')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.authentication" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-8" v-if="authentication == 'token'">
                <label for="token">Token</label>
                <input type="text" class="form-control" name="token" id="token" v-model="token">
                <span v-show="getHttpErrors.hasOwnProperty('token')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.token" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row" v-if="authentication == 'jwt'">
            <div class="form-group col-md-6">
                <label for="username">Usuário</label>
                <input type="text" class="form-control" name="username" id="username" v-model="username">
                <span v-show="getHttpErrors.hasOwnProperty('username')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.username" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <label for="password">Senha</label>
                <div class="input-group">
                    <input :type="showPassword ? 'text' : 'password'" class="form-control" name="password" id="password" v-model="password">
                    <div class="input-group-append">
                        <button class="btn btn-dark" @click="showPassword = !showPassword" data-toggle="tooltip" title="Mostrar/Esconder">
                            <svg v-if="showPassword" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>
                <span v-show="getHttpErrors.hasOwnProperty('password')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.password" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/apis" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeApi">Salvar</button>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    data() {
        return {
            showPassword: false
        }
    },
    props: {
        apiId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        if (this.apiId) {
            this.loadApi(this.apiId)
        }
    },
    methods: {
        ...mapActions('apis', [
            'storeApi', 'loadApi'
        ])
    },
    computed: {
        ...mapGetters('apis', ['getHttpErrors']),
        authentications() {
            return this.$store.state.apis.authentications
        },
        name: {
            get() {
                return this.$store.state.apis.name
            },
            set(value) {
                this.$store.commit('apis/setName', value)
            }
        },
        baseUrl: {
            get() {
                return this.$store.state.apis.baseUrl
            },
            set(value) {
                this.$store.commit('apis/setBaseUrl', value)
            }
        },
        authentication: {
            get() {
                return this.$store.state.apis.authentication
            },
            set(value) {
                this.$store.commit('apis/setAuthentication', value)
            }
        },
        token: {
            get() {
                return this.$store.state.apis.token
            },
            set(value) {
                this.$store.commit('apis/setToken', value)
            }
        },
        username: {
            get() {
                return this.$store.state.apis.username
            },
            set(value) {
                this.$store.commit('apis/setUsername', value)
            }
        },
        password: {
            get() {
                return this.$store.state.apis.password
            },
            set(value) {
                this.$store.commit('apis/setPassword', value)
            }
        },
    }
}
</script>
