<template>
    <div>
        <div class="modal fade" :class="{ 'show': showModal }" :style="{'display': showModal ? 'block' : 'none'}" id="variablesModal" tabindex="-1" role="dialog" aria-labelledby="variablesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="variablesModalLabel">Variáveis disponíveis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fas-fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        aqui vai as variáveis
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn" @click.prevent="showing == false">Cancelar</button> -->
                        <button type="button" class="btn btn-primary" @click.prevent="selectVar">Incluir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showModal: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        selectVar() {
            this.$emit('modalClose', 1);
        }
    }
}
</script>
