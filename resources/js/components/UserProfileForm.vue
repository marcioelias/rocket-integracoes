<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="field">Ativo</label>
                <div class="form-control">{{ active ? 'Sim' : 'Não' }}</div>
            </div>
            <div class="form-group col-md-5">
                <label for="name">Nome</label>
                <div class="form-control">{{ name }}</div>
            </div>
            <div class="form-group col-md-5">
                <label for="email">Email</label>
                <div class="form-control">{{ email }}</div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="username">Usuário</label>
                <div class="form-control">{{ username }}</div>
            </div>
            <div class="form-group col-md-3 d-flex flex-column justify-content-end">
                <button class="btn btn-primary btn-lg d-block" @click="onClickChangePassword()" :disabled="changePassword">
                    <i class="fas fa-key"></i> Alterar Senha
                </button>
            </div>
        </div>
        <div class="form-row" v-if="changePassword">
            <div class="form-group col-md-4">
                <label for="current_password">Senha atual</label>
                <input ref="inputCurrentPassword" type="password" name="current_password" id="current_password" class="form-control" v-model="currentPassword">
                <span v-if="getHttpErrors.old_password" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.old_password" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="password">Nova senha</label>
                <input type="password" name="password" id="password" class="form-control" v-model="newPassword">
                <span v-if="getHttpErrors.password" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.password" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-4">
                <label for="password_confirmation">Confirmação de Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" v-model="newPasswordConfirmation">
                <span v-if="getHttpErrors.password_confirmation" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.password_confirmation" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3" v-if="changePassword">
            <button class="btn mt-3 mr-3" @click="cancelChange()">Cancelar</button>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="changeUserPassword">Salvar</button>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    props: {
        userId: {
            type: Number,
            required: true
        }
    },
    mounted() {
        if (this.userId) {
            this.loadUserProfile(this.userId)
        }
    },
    methods: {
        ...mapActions('profiles', [
            'loadUserProfile', 'changeUserPassword'
        ]),
        cancelChange() {
            this.changePassword = false
            this.currentPassword = ''
            this.newPassword = ''
            this.newPasswordConfirmation = ''
        },
        onClickChangePassword() {
            this.changePassword = true
            this.$nextTick(() => $(this.$refs['inputCurrentPassword']).focus())

        }
    },
    computed: {
        ...mapGetters('profiles', [
            'getHttpErrors'
        ]),
        active() {
            return this.$store.state.profiles.active
        },
        name() {
            return this.$store.state.profiles.name
        },
        email() {
            return this.$store.state.profiles.email
        },
        username() {
            return this.$store.state.profiles.username
        },
        currentPassword: {
            get() {
                return this.$store.state.profiles.currentPassword
            },
            set(value) {
                this.$store.commit('profiles/setCurrentPassword', value)
            }
        },
        newPassword: {
            get() {
                return this.$store.state.profiles.newPassword
            },
            set(value) {
                this.$store.commit('profiles/setNewPassword', value)
            }
        },
        newPasswordConfirmation: {
            get() {
                return this.$store.state.profiles.newPasswordConfirmation
            },
            set(value) {
                this.$store.commit('profiles/setNewPasswordConfirmation', value)
            }
        },
        changePassword: {
            get() {
                return this.$store.state.profiles.changePassword
            },
            set(value) {
                this.$store.commit('profiles/setChangePassword', value)
            }
        }
    }
}
</script>
