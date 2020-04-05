<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="webhook_id">Webhook</label>
                <select id="webhook_id" name="webhook_id" class="form-control" v-model="webhookId">
                    <option v-for="wh in webhooks" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
                </select>
                <span v-show="getHttpErrors.hasOwnProperty('webhook_id')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.webhook_id" :key="index">{{ error }}</strong>
                </span>
            </div>
           <div class="form-group col-md-6">
                <label for="product_code">Código</label>
                <input type="text" name="product_code" id="product_code" class="form-control" v-model="productCode">
                <span v-show="getHttpErrors.hasOwnProperty('product_code')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.product_code" :key="index">{{ error }}</strong>
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
        <div class="mt-3">
            <a href="/products" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeProduct">Salvar</button>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    props: {
        productId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        this.loadWebhooks()
        if (this.productId) {
            this.loadProduct(this.productId)
        }
    },
    methods: {
        ...mapActions('products', [
            'storeProduct', 'loadProduct', 'loadWebhooks'
        ]),
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
                this.$store.commit('products/setWebhookId', value)
            }
        },
        webhooks() {
            return this.$store.state.products.webhooks
        }
    }
}
</script>
