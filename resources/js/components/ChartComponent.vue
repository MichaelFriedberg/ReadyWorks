<template>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h1>Top 10 Computer Models</h1>
            <canvas id="top-ten-chart"></canvas>
        </div>
        <div class="col-sm-12 col-md-6">
            <h1>Computers by Operating System</h1>
            <canvas id="os-chart"></canvas>
        </div>
        <div class="col-sm-12 col-lg-8 mt-5">
            <h1>Computer Count by Location</h1>
            <canvas id="location-chart"></canvas>
        </div>
    </div>
</template>

<script>
    import Chart from 'chart.js';
    import 'chartjs-plugin-colorschemes';

    export default {
        name: "ChartComponent",
        data() {
            return {
                readyWorksChartData1: {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        align: 'bottom',
                        datasets: [
                            {
                                data: [],
                                borderWidth: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        lineTension: 1,
                        legend: {
                            position: 'right'
                        },
                        plugins: {
                            colorschemes: {
                                scheme: 'brewer.Paired12'
                            }
                        }
                    }
                },
                readyWorksChartData2: {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        align: 'bottom',
                        datasets: [
                            {
                                data: [],
                                borderWidth: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        lineTension: 1,
                        legend: {
                            display: true,
                            position: 'right'
                        },
                        plugins: {
                            colorschemes: {
                                scheme: 'brewer.Paired12'
                            }
                        }
                    }
                },
                readyWorksChartData3: {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                data: [],
                                borderWidth: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        lineTension: 1,
                        legend: {
                            display: false,
                        },
                        plugins: {
                            colorschemes: {
                                scheme: 'brewer.Paired12'
                            }
                        }
                    }
                }
            }
        },
        methods: {
            loadData(chartObj,endpoint, chartId) {
                axios
                    .get(endpoint)
                    .then(
                        (response) => {
                            chartObj.data.datasets[0].data = response.data.totals;
                            chartObj.data.labels = response.data.labels;
                            const ctx = document.getElementById(chartId);
                            new Chart(ctx, chartObj);
                        })
            }
        },
        mounted() {
            this.loadData(this.readyWorksChartData1, `/api/chart/top10`, 'top-ten-chart');
            this.loadData(this.readyWorksChartData2, `/api/chart/os`, 'os-chart');
            this.loadData(this.readyWorksChartData3, `/api/chart/location`, 'location-chart');
        }
    }
</script>

<style scoped>

</style>
