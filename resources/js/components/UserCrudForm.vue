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
            <div class="form-group col-md-5">
               <label for="name">Nome</label>
                <input type="text" name="name" id="name" class="form-control" v-model="name">
                <span v-if="getHttpErrors.name" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-5">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" v-model="email">
                <span v-if="getHttpErrors.email" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.email" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="username">Usuário</label>
                <input type="text" name="username" id="username" class="form-control" v-model="username" :disabled="this.userId">
                <span v-if="getHttpErrors.username" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.username" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4" v-if="!this.userId">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control" v-model="password">
                <span v-if="getHttpErrors.password" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.password" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4" v-if="!this.userId">
                <label for="password_confirmation">Confirmação de Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" v-model="passwordConfirmation">
                <span v-if="getHttpErrors.password_confirmation" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.password_confirmation" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3">
            <a href="/users" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeUser">Salvar</button>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    props: {
        userId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        if (this.userId) {
            this.loadUser(this.userId)
        }
    },
    methods: {
        ...mapActions('users', [
            'loadUser', 'storeUser'
        ])
    },
    computed: {
        ...mapGetters('users', [
            'getHttpErrors'
        ]),
        name: {
            get() {
                return this.$store.state.users.name
            },
            set(value) {
                this.$store.commit('users/setName', value)
            }
        },
        email: {
            get() {
                return this.$store.state.users.email
            },
            set(value) {
                this.$store.commit('users/setEmail', value)
            }
        },
        username: {
            get() {
                return this.$store.state.users.username
            },
            set(value) {
                this.$store.commit('users/setUsername', value)
            }
        },
        password: {
            get() {
                return this.$store.state.users.password
            },
            set(value) {
                this.$store.commit('users/setPassword', value)
            }
        },
        passwordConfirmation: {
            get() {
                return this.$store.state.users.passwordConfirmation
            },
            set(value) {
                this.$store.commit('users/setPasswordConfirmation', value)
            }
        },
        active: {
            get() {
                return this.$store.state.users.active
            },
            set(value) {
                this.$store.commit('users/setActive', value)
            }
        }
    }
}
</script>
