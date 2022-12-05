<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div class="box-statistikk">
        <div class="info">
            <p class="title">{{ title }}</p>
            <h3 class="value">{{ antallBrukere }}</h3>
        </div>
        <div class="chart-div" :class="chartType">
            <canvas id="boxStatistikk" style="width: 100px; height: 100px"></canvas>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface'
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import DeltaDate from '../objects/DeltaDate';

declare var ajaxurl: string; // Kommer fra global

@Component
export default class BoxStatistikk extends Vue {
    @Prop() subaction!: string;
    @Prop() title!: string;
    @Prop() labels!: string[];

    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, ajaxurl);
    public deltaDates : DeltaDate[] = [];
    private chart : any;
    public antallBrukere : number = 0;
    public chartType = 'doughnut';

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
        this.antallBrukere = response.antall;
        this.genererChart(response.antall_ikke_brukt);
        
        return response;
    }

    genererChart(antallIkkeBrukt : number) : void {
        var antall : number[] = [this.antallBrukere-antallIkkeBrukt, antallIkkeBrukt];
        var max = 0;

        // for(var d of this.deltaDates) {
        //     max = d.getTotal() > max ? d.getTotal() : max;
        //     labels.push(this.selectedButton == 'day' ? d.getHourString() : d.getDateString())
        //     antal.push(d.getTotal());
        // }

        var barColors = ["#ee6f58", "#60aa96"];
        
        this.chart = new Chart("boxStatistikk", {
            type: this.chartType,
            data: {
                labels: this.labels,
                datasets: [{
                    label: 'hide',
                    backgroundColor: barColors,
                    data: antall,
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

</style>
