<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="label">Rótulo</label>
                <input type="text" name="label" id="label" class="form-control" v-model="label">
                <span v-show="getHttpErrors.hasOwnProperty('label')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.label" :key="index">{{ error }}</strong>
                </span>
            </div>
            <div class="form-group col-md-6">
                <label for="name">Nome do Campo</label>
                <input type="text" name="name" id="name" class="form-control" v-model="name">
                <span v-show="getHttpErrors.hasOwnProperty('field_name')" class="invalid-feedback" style="display: block">
                    <strong v-for="(error, index) in getHttpErrors.field_name" :key="index">{{ error }}</strong>
                </span>
            </div>
        </div>
        <div class="mt-3">
            <!-- <button class="btn mt-3 mr-3">Cancelar</button> -->
            <a href="/fields" class="btn mt-3 mr-3">Cancelar</a>
            <button type="button" class="btn btn-secondary mt-3" data-toggle="tooltip" titlte="Salvar" @click.prevent="storeField">Salvar</button>
        </div>
    </div>
</template>

<script>
import { mapActions, getters, mapGetters } from 'vuex'

export default {
    props: {
        fieldId: {
            type: Number,
            default: null
        }
    },
    mounted() {
        if (this.fieldId) {
            this.loadField(this.fieldId)
        }
    },
    methods: {
        ...mapActions('fields', [
            'loadField', 'storeField'
        ])
    },
    computed: {
        ...mapGetters('fields', [
            'getHttpErrors'
        ]),
        name: {
            get() {
                return this.$store.state.fields.name
            },
            set(value) {
                this.$store.commit('fields/setName', value)
            }
        },
        label: {
            get() {
                return this.$store.state.fields.label
            },
            set(value) {
                this.$store.commit('fields/setLabel', value)
            }
        }
    }
}
</script>
