<template>
    <div>
        <div class="form-row">
           <div class="form-group col-md-6">
                <label for="product_code">Código</label>
                <input type="text" name="product_code" id="product_code" class="form-control" v-model="productCode">
                <span v-show="getHttpErrors.hasOwnProperty('product_code')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.product_code" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <label for="webhook_id">Webhook</label>
                <select id="webhook_id" name="webhook_id" class="form-control" v-model="webhookId">
                    <option v-for="wh in webhooks" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
                </select>
                <span v-show="getHttpErrors.hasOwnProperty('webhook_id')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.webhook_id" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" class="form-control" v-model="name">
                <span v-show="getHttpErrors.hasOwnProperty('name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.name" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="card">
            <div class="card-header pl-3 pt-2 pb-1 pr-2">
                <h5 class="float-left mt-2">Ações</h5>
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in" apper>
                    <button class="btn btn-sm btn-secondary float-right" data-toggle="tooltip" title="Incluir Ação" @click="doOnAddEditAction" v-show="!showActionForm"><i class="fas fa-lg fa-plus"></i></button>
                </transition>
            </div>
            <div class="card-body p-0">
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in" apper>
                    <product-actions-table v-if="!showActionForm" key="table" @onEditAction="doOnEditAction" />
                    <product-actions-crud-form @onCancel="doOnCancelActionCrud()" @onAddAction="doOnAddAction" :action-idx="actionEditId" :is-editing="isEditingAction" v-else key="crud" />
                </transition>
            </div>
        </div>
        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in" appear>
            <div class="mt-3" v-if="!showActionForm">
                <a href="/products" class="btn mt-3 mr-3">Cancelar</a>
                <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeProduct">Salvar</button>
            </div>
        </transition>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ProductActionsTable from './ProductActionsTable'
import ProductActionsCrudForm from './ProductActionsCrudForm'
export default {
    components: {
        ProductActionsTable,
        ProductActionsCrudForm
    },
    data() {
        return {
            showActionForm: false,
            actionEditId: null,
            isEditingAction: false
        }
    },
    props: {
        productId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        this.loadWebhooks()
        this.loadApis()
        this.loadVariables()
        if (this.productId) {
            this.loadProduct(this.productId)
        }
    },
    methods: {
        ...mapActions('products', [
            'storeProduct', 'loadProduct', 'loadWebhooks', 'loadApis', 'loadVariables'
        ]),
        doOnCancelActionCrud() {
            this.showActionForm = false
        },
        doOnAddAction() {
            this.showActionForm = false
        },
        doOnEditAction(id) {
            this.isEditingAction = true
            this.actionEditId = id
            this.showActionForm = true
        },
        doOnAddEditAction() {
            if (!this.webhookId) {
                swal({
                    title: 'Ooops!',
                    text: 'Selecine uma Webhook para continuar.',
                    type: 'error',
                    confirmButtonText: 'Ok',
                    padding: '2em'
                })
            } else {
                this.isEditingAction = false
                this.actionEditId = null
                this.showActionForm = true
            }
        }
    },
    computed: {
        ...mapGetters('products', [
            'getHttpErrors'
        ]),
        name: {
            get() {
                return this.$store.state.products.name
            },
            set(value) {
                this.$store.commit('products/setName', value)
            }
        },
        productCode: {
            get() {
                return this.$store.state.products.productCode
            },
            set(value) {
                this.$store.commit('products/setProductCode', value)
            }
        },
        webhookId: {
            get() {
                return this.$store.state.products.webhookId
            },
            set(value) {
                this.$store.dispatch('products/selectWebhookId', value)
            }
        },
        webhooks() {
            return this.$store.state.products.webhooks
        }
    }
}
</script>

<style>
    .animated {
        animation-duration: 300ms;
        /* animation-delay: 1s; */
        /* animation-iteration-count: infinite; */
    }
</style>
