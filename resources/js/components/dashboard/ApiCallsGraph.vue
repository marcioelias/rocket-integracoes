<template>
    <vue-apex-charts type="donut" :options="chartOptions" :series="series" />
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
export default {
    components: {
        VueApexCharts
    },
    mounted() {
        this.getData()
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'donut',
                    width: 380,
                    /* animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 10,
                        animateGradually: {
                            enabled: true,
                            delay: 1150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed:  50
                        }
                    } */
                },
                colors: ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                    width: 10,
                    height: 10,
                    },
                    itemMargin: {
                    horizontal: 0,
                    vertical: 8
                    }
                },
                plotOptions: {
                pie: {
                    donut: {
                    size: '55%',
                    background: 'transparent',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '29px',
                            fontFamily: 'Nunito, sans-serif',
                            color: undefined,
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '26px',
                            fontFamily: 'Nunito, sans-serif',
                            color: '#bfc9d4',
                            offsetY: 16,
                            formatter: function (val) {
                                return val
                            }
                        },
                        total: {
                        show: true,
                        showAlways: true,
                        label: 'Total',
                        color: '#888ea8',
                        formatter: function (w) {
                            return w.globals.seriesTotals.reduce( function(a, b) {
                            return a + b
                            }, 0)
                        }
                        }
                    }
                    }
                }
                },
                stroke: {
                    show: true,
                    opacity: 0,
                    width: 10,
                    colors: '#0e1726'
                },
                labels: ['Sucesso', 'Falha'],
                responsive: [{
                    breakpoint: 1599,
                    options: {
                        chart: {
                            width: '350px',
                            height: '300px'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    },

                    breakpoint: 1439,
                    options: {
                        chart: {
                            width: '270px',
                            height: '300px'
                        },
                        legend: {
                            position: 'bottom'
                        },
                        plotOptions: {
                        pie: {
                            donut: {
                            size: '75%',
                            }
                        }
                        }
                    },
                }]
            },
            series: [0, 0],
        }
    },
    methods: {
        async getData() {
            await axios.get('/json/graph/api_calls')
                .then(r => {
                    this.series = r.data.series
                    //this.chartOptions.labels = r.data.labels
                })
        }
    }
}
</script>
