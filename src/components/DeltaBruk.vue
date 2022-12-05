<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div>
        <div v-if="initialized">
            <div class="filter-buttons">
                <button @click="changeTimeline('day')" :class="{'correct-button' : selectedButton == 'day'}" class="ukm-botton-style">I dag</button>
                <button @click="changeTimeline('week')" :class="{'correct-button' : selectedButton == 'week'}" class="ukm-botton-style">Siste uke</button>
                <button @click="changeTimeline('month')" :class="{'correct-button' : selectedButton == 'month'}" class="ukm-botton-style">Siste måned</button>
            </div>
            <div>
                <canvas id="deltaBrukereChart" style="width:100%;max-width:600px"></canvas>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface'
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import DeltaDate from '../objects/DeltaDate';

type TimelineButton = "day" | "week" | "month";


@Component
export default class DeltaBrukKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, 'https://ukm.dev/2023-deatnu-tana-deatnu-tana-sorelv/wp-admin/');
    public deltaDates : DeltaDate[] = [];
    public selectedButton : TimelineButton = 'day';

    // Opprett nettsiden
    init() : void {
        // Get data via ajax
        this.initialized = true;
        this.fetchData();

    }

    public changeTimeline(selectedButton : TimelineButton) {
        this.selectedButton = selectedButton;
        this.fetchData();
    }

    public async fetchData() {
        var data = {
            action: 'UKMstatistikk_ajax',
            subaction: 'getBrukereDelta',
            from_date: '2020-01-01 12:15:00'
        };

        var response = await this.spaInteraction.runAjaxCall('admin-ajax.php/', 'POST', data);
        this.deltaDates = [];
        for(var $r of response) {
            this.deltaDates.push(new DeltaDate($r['date'], $r['total']))
        }
        
        this.genererChart();
        
        return response;
    }

    genererChart() : void {
        var labels : string[] = [];
        var antal : number[] = [];

        for(var d of this.deltaDates) {
            labels.push(d.getDateString())
            antal.push(d.getTotal());
        }


        new Chart("deltaBrukereChart", {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
            fill: true,
            lineTension: 0,
            backgroundColor: "rgba(0,0,255,.1)",
            borderColor: "rgba(0,0,255,0.1)",
            data: antal
            }]
        },
        options: {
            legend: {display: false},
            scales: {
            yAxes: [{ticks: {min: 6, max:16}}],
            }
        }
        });
    }
}
</script>

<style>
.filter-buttons {
    padding: 10px;
    padding-bottom: 30px;
    padding-top: 20px;
}
</style>
