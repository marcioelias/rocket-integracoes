<template>
    <div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Ação</th>
                        <th>Api</th>
                        <th>Endpoint</th>
                        <th class="text-center">Ativo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(action, index) in actions" :key="index">
                        <td>{{ action.event.name }}</td>
                        <td>{{ action.name }}</td>
                        <td>{{ action.api_endpoint.api.name }}</td>
                        <td>{{ action.api_endpoint.name }}</td>
                        <td class="text-center">
                            <i class="far fa-lg cursor-pointer"
                                :class="{'fa-check-square text-success': action.active, 'fa-square text-danger': !action.active}"
                                data-toggle="tooltip" title="Ativar/Desativar"
                                @click="activateAction(index)"></i>
                        </td>
                        <td class="text-center">
                            <span data-toggle="tooltip" title="Editar" @click="editAction(index)">
                                <i class="fas fa-pen fa-lg edit cursor-pointer mr-2" ></i>
                            </span>
                            <span data-toggle="tooltip" title="Remover" @click="removeAction(index)">
                                <i class="fas fa-trash-alt fa-lg delete cursor-pointer"></i>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'
export default {
    computed: {
        ...mapGetters('products', [
            'actionByIdx'
        ]),
        actions() {
            return this.$store.state.products.actions
        },
    },
    methods: {
        activateAction(id) {
             this.$store.commit('products/setActionActive', id)
        },
        removeAction(id) {
            let self = this
            swal({
                title: 'Remover o registro '+self.actionByIdx(id).name,
                text: "Essa ação será persistida somente quando o produto for salvo",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Remover',
                padding: '2em'
                }).then(function(result) {
                    if (result.value) {
                        self.$store.commit('products/removeAction', id)
                        swal(
                            'Ok!',
                            'Registro '+self.actionByIdx(id).name+' foi removido com sucesso.',
                            'success'
                        )
                    }
                })
        },
        editAction(id) {
            this.$emit('onEditAction', id)
        }
    }
}
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .table-hover tbody tr:hover i.edit {
        color: #e0dd1c !important;
    }

    .table-hover tbody tr:hover i.delete {
        color: #e7515a !important;
    }
</style>
