<template>
    <div>
        <div class="form-container outer">
            <div class="form-form">
                <div class="form-form-wrap">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">Alteração de Senha</h1>
                            <hr>
                            <form>
                                <div class="form">
                                    <input type="hidden" name="token" v-model="token">
                                    <div class="form-row">
                                        <div class="form-group col">
                                                <label for="email" class="float-left">Email</label>
                                                <input id="email" name="email" type="text" class="form-control" value="" placeholder="Email" v-model="email">
                                                <span v-show="httpErrors.hasOwnProperty('email')" class="invalid-feedback d-flex align-content-start" style="display: block">
                                                    <strong v-for="(error, index) in httpErrors.email" :key="index">{{ error }}</strong>
                                                </span>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="password" class="float-left">Senha</label>
                                            <input id="password" name="password" type="password" class="form-control" value="" v-model="password">
                                            <span v-show="httpErrors.hasOwnProperty('password')" class="invalid-feedback d-flex align-content-start" style="display: block">
                                                <strong v-for="(error, index) in httpErrors.password" :key="index">{{ error }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label for="password_confirmation" class="float-left">Confirmação de Senha</label>
                                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" value="" v-model="password_confirmation">
                                            <span v-show="httpErrors.hasOwnProperty('password_confirmation')" class="invalid-feedback d-flex align-content-start" style="display: block">
                                                <strong v-for="(error, index) in httpErrors.password_confirmation" :key="index">{{ error }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-sm-flex justify-content-between mt-3">

                                        <div class="field-wrapper">
                                            <button type="button" @click.prevent="sendEmailReset()" class="btn btn-primary" value="">Enviar</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            password: '',
            password_confirmation: '',
            httpErrors: {}
        }
    },
    props: {
        token: {
            type: String,
            required: true
        },
        email: {
            type: String,
            required: true
        }
    },
    methods: {
        async sendEmailReset() {
            let self = this
            let post = {
                'email': this.email,
                'token': this.token,
                'password': this.password,
                'password_confirmation': this.password_confirmation
            }
            this.httpErrors = {}
            await axios.post('/password/reset', post)
                .then(r => {
                    if (r.status === 200) {
                        swal({
                            title: 'Sucesso!',
                            text: 'Senha alterada com sucesso.',
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
    }
}
</script>
