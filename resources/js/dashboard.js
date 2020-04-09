import Vue from 'vue'
import ApiCallsGraph from './components/dashboard/ApiCallsGraph.vue'
import LastApiCalls from './components/dashboard/LastApiCalls.vue'
import VueApexCharts from 'vue-apexcharts'

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr('content');

Vue.use(VueApexCharts)

new Vue({
    el: '#dashboard',
    components: {
        ApiCallsGraph,
        LastApiCalls
    }
})
