<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div v-if="initialized">
        <h4>Generelt</h4>
        <div class="box-statistikk-div">
            <box-statistikk ref="antall-brukere" :title="'Antall brukere'" :labels="['Ubrukte', 'Brukte']" :subaction="'getTotalBrukereDelta'" :initialEnthusiasm="5" />
            
            <div class="box-statistikk">
                <div class="info">
                    <p class="title">Brukere gjennom Facebook</p>
                    <h3 class="value">--</h3>
                </div>
                <div></div>
            </div>

            <div class="box-statistikk">
                <div class="info">
                    <p class="title">Bedt om nytt passord</p>
                    <h3 class="value">--</h3>
                </div>
                <div></div>
            </div>

        </div>
        <h4>Bruk av påmledingssystemet</h4>
        <div>
            <div class="filter-buttons">
                <button @click="changeTimeline('day')" :class="{'correct-button' : selectedButton == 'day'}" class="ukm-botton-style">I dag</button>
                <button @click="changeTimeline('week')" :class="{'correct-button' : selectedButton == 'week'}" class="ukm-botton-style">Siste uke</button>
                <button @click="changeTimeline('month')" :class="{'correct-button' : selectedButton == 'month'}" class="ukm-botton-style">Siste måned</button>
            </div>

            <div class="box-statistikk-div">
                <div class="box-statistikk">
                    <div class="info">
                        <p class="title">Antall</p>
                        <h3 class="value">{{ totalBrukereSelected }}</h3>
                    </div>
                    <div></div>
                </div>
            </div>

            <div>
                <canvas class="box-statistikk" id="deltaBrukereChart" style="width:100%; max-width: 1200px;"></canvas>
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
import BoxStatistikk from "./BoxStatistikk.vue";

type TimelineButton = "day" | "week" | "month";
declare var ajaxurl: string; // Kommer fra global

@Component
export default class DeltaBrukKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, ajaxurl);
    public deltaDates : DeltaDate[] = [];
    public selectedButton : TimelineButton = 'day';
    private chart : any;
    public totalBrukereSelected : number = 0;

    public components = [BoxStatistikk];

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

    private getTimeline() : string {
        var now = new Date();

        if(this.selectedButton == 'week') {
            now = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
        }
        // Month
        else if(this.selectedButton == 'month') {
            now.setMonth(now.getMonth() - 1);
        }

        // YYYY-MM-DD
        return now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();
    }

    public async fetchData() {
        var data = {
            action: 'UKMstatistikk_ajax',
            subaction: 'getBrukereDelta',
            from_date: this.getTimeline(),
            timeline_name : this.selectedButton
        };
        

        var response = await this.spaInteraction.runAjaxCall('/', 'POST', data);
        this.deltaDates = [];
        this.totalBrukereSelected = 0;

        for(var $r of response) {
            var deltaDate = new DeltaDate($r['date'], parseInt($r['total']));
            this.deltaDates.push(deltaDate)
            this.totalBrukereSelected += deltaDate.getTotal();
        }
        
        this.genererChart();
        
        return response;
    }

    genererChart() : void {
        var labels : string[] = [];
        var antal : number[] = [];
        var max = 0;

        for(var d of this.deltaDates) {
            max = d.getTotal() > max ? d.getTotal() : max;
            labels.push(this.selectedButton == 'day' ? d.getHourString() : d.getDateString())
            antal.push(d.getTotal());
        }

        console.log(max);

        this.chart = new Chart("deltaBrukereChart", {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    data: antal,
                    lineTension: 0.2,
                    borderColor: '#ee6f58',
                    backgroundColor: 'rgba(0,0,0,0.0)',
                    pointBackgroundColor: ['white', 'white', 'white', 'white', 'white', 'white', '#ee6f58'],
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                    gridLines: {
                        display: false,
                    },
                    ticks: {
                        fontSize: 15,
                        fontColor: 'lightgrey',
                        stepSize: 1,
                    }
                    }],
                    yAxes: [{
                    gridLines: {
                        drawBorder: false,
                    },
                    ticks: {
                        beginAtZero: true,
                        fontSize: 15,
                        fontColor: 'lightgrey',
                        maxTicksLimit: 5,
                        padding: 25,
                    }
                    }]
                },
                tooltips: {
                    backgroundColor: '#1e90ff'
                }
            },
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
