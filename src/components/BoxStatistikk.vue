<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div class="box-statistikk">
        <div class="info">
            <p :class="{'phantom-loading' : loading}" class="title">{{ title }}</p>
            <h3 :class="{'phantom-loading' : loading}" class="value">{{ antall }}</h3>
        </div>
        <div v-show="!loading" class="chart-div" :class="chartType">
            <canvas :id="chartId" class="box-statstikk-100" style="width: 100px; height: 100px"></canvas>
        </div>
        <!--- Placeholder loading -->
        <div v-show="loading">
            <div class="chart-placholder phantom-loading"><div class="background-placeholder"></div></div>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface'
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import DeltaDate from '../objects/DeltaDate';
import {v4 as uuidv4} from 'uuid';


declare var ajaxurl: string; // Kommer fra global

@Component
export default class BoxStatistikk extends Vue {
    @Prop() subaction! : string;
    @Prop() title! : string;
    @Prop() labels! : string[];
    @Prop() chartkeys! : string[];
    @Prop() loading! : boolean;

    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, ajaxurl);
    public deltaDates : DeltaDate[] = [];
    private chart : any;
    public antall : number = 0;
    public chartType = 'doughnut';
    public chartId = uuidv4();

    // Opprett nettsiden
    mounted() : void {
        this.fetchData()
    }

    public async fetchData() {
        var data = {
            action: 'UKMstatistikk_ajax',
            subaction: this.subaction,
        };

        var response = await this.spaInteraction.runAjaxCall('/', 'POST', data);
        this.antall = response.antall;
        this.genererChart([response[this.chartkeys[0]], response[this.chartkeys[1]]]);
        
        return response;
    }

    genererChart(args : number[]) : void {
        var max = 0;

        var barColors = ["#60aa96", "#ee6f58"];

        this.chart = new Chart(this.chartId, {
            type: this.chartType,
            data: {
                labels: this.labels,
                datasets: [{
                    label: 'hide',
                    backgroundColor: barColors,
                    data: args,
                    lineTension: 0.2,
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: false,
                },
            },
        });
        console.log(this.chart);
    }
}

Vue.component('box-statistikk', BoxStatistikk);

</script>

<style>
.chart-placholder {
    width: 100px;
    height: 100px;
    border: solid 23px #0000000a;
    border-radius: 50%;
    margin: auto;
    margin-top: 0;
    margin-left: 30px;
}
.chart-placholder .background-placeholder {
    background: #fff !important;
    height: 100px;
    width: 100px;
}
.box-statstikk-100 {
    width: 100px !important;
    height: 100px !important;
}
</style>
