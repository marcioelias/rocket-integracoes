<template>
    <div>
        <div class="form-container outer">
            <div class="form-form">
                <div class="form-form-wrap">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">Recuperação de Senha</h1>
                            <p class="signup-link recovery">Informe seu e-mail para obter informações sobre como recuperar sua senha.</p>
                            <form>
                                <div class="form">

                                    <div id="email-field" class="field-wrapper input">
                                        <div class="d-flex justify-content-between">
                                            <label for="email">EMAIL</label>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                        <input id="email" name="email" type="text" class="form-control" value="" placeholder="Email" v-model="email">
                                        <span v-show="httpErrors.hasOwnProperty('email')" class="invalid-feedback d-flex align-content-start" style="display: block">
                                            <strong v-for="(error, index) in httpErrors.email" :key="index">{{ error }}</strong>
                                        </span>
                                    </div>

                                    <div class="d-sm-flex justify-content-between">

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
            email: '',
            httpErrors: {}
        }
    },
    methods: {
        async sendEmailReset() {
            let self = this
            let post = {
                'email': this.email
            }
            this.httpErrors = {}
            await axios.post('/password/email', post)
                .then(r => {
                    if (r.status === 200) {
                        swal({
                            title: 'Sucesso!',
                            text: 'E-mail enviado com sucesso.',
                            type: 'success',
                            confirmButtonText: 'Ok',
                            padding: '2em'
                        }).then(function(result) {
                            self.email = ''
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
