<template>
    <div>
        <div class="widget widget-table-two">

            <div class="widget-heading">
                <h5 class="">Últimas chamadas de API</h5>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table" style="overflow: hidden">
                        <thead>
                            <tr>
                                <th><div class="th-content">Api</div></th>
                                <th><div class="th-content">Endpoint</div></th>
                                <th><div class="th-content">Data/Hora</div></th>
                                <th><div class="th-content">Status</div></th>
                            </tr>
                        </thead>
                        <!-- <tbody> -->
                            <transition-group name="list" tag="tbody" appear>
                                <tr v-for="apiCall in apiCalls" :key="apiCall.id">
                                    <td><div class="td-content">{{ apiCall.api_endpoint.api.name }}</div></td>
                                    <td><div class="td-content">{{ apiCall.api_endpoint.name }}</div></td>
                                    <td><div class="td-content">{{ apiCall.created_at }}</div></td>
                                    <td>
                                        <div class="td-content">
                                            <i class="fas fa-check fa-2x text-success" data-toggle="tooltip" title="Sucesso" v-if="apiCall.success"></i>
                                            <i class="fas fa-bug fa-2x text-danger" data-toggle="tooltip" title="Falha" v-else></i>
                                        </div>
                                    </td>
                                </tr>
                            </transition-group>
                        <!-- </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            apiCalls: []
        }
    },
    mounted() {
        this.getLastApiCalls()
    },
    methods: {
        async getLastApiCalls() {
            await axios.get('/json/last_api_calls')
                .then(r => {
                    console.log(r.data)
                    this.apiCalls = r.data
                })
        }
    }
}
</script>

<style>
.list-enter-active, .list-leave-active {
  transition: 2s ease;
}
.list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateY(15px);
}
</style>
