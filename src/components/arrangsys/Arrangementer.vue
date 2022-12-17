<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div v-if="initialized">
        <h4>Arrangementer</h4>
        <div class="box-statistikk-div">
            <box-statistikk ref="antall-brukere" :loading="loading" :title="'Antall arrangementer'" :labels="['Kommune', 'Fylke']" :chartkeys="['antall_kommune', 'antall_fylke']" :subaction="'getAntallArrangementer'"  />

            <!-- <box-statistikk-enkel ref="antall-brukere-facebook" :title="'Antall gjennom Facebook'" :subaction="'getTotalBrukereFbDelta'" /> -->
            <box-statistikk-enkel ref="antall-passord-bedt" :title="'Antall arrangementer i år'" :subaction="'getAntallArrangementer'" />
            <box-statistikk-enkel ref="antall-passord-bedt" :title="'Antall arrangementer i fjor'" :subaction="'getAntalPass'" />
        </div>
        <h4>Bruk av påmeldingssystemet</h4>
        <div>
            <div class="filter-buttons">
                <button @click="changeTimeline('day')" :class="{'correct-button' : selectedButton == 'day', 'phantom-loading' : loading }" class="ukm-botton-style">I dag</button>
                <button @click="changeTimeline('week')" :class="{'correct-button' : selectedButton == 'week', 'phantom-loading' : loading }" class="ukm-botton-style">Siste uke</button>
                <button @click="changeTimeline('month')" :class="{'correct-button' : selectedButton == 'month', 'phantom-loading' : loading }" class="ukm-botton-style">Siste måned</button>
            </div>

            <div class="box-statistikk-div">
                <div class="box-statistikk">
                    <div class="info">
                        <p class="title">Antall</p>
                        <h3 :class="{'phantom-loading' : loadingChart}" class="value">{{ totalBrukereSelected }}</h3>
                    </div>
                    <div></div>
                </div>
            </div>

            <div :class="{'phantom-loading' : loadingChart}" class="chart-full-div">
                <canvas class="box-statistikk" id="deltaBrukereChart" style="width:100%; max-width: 1200px;"></canvas>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../../interfaces/tabInterface'
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import DeltaDate from '../../objects/DeltaDate';
import BoxStatistikk from "../BoxStatistikk.vue";
import BoxStatistikkEnkel from "../BoxStatistikkEnkel.vue";

type TimelineButton = "day" | "week" | "month";
declare var ajaxurl: string; // Kommer fra global

@Component
export default class ArrangementerKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;
    public loading : boolean = true;
    public loadingChart : boolean = true;
    private spaInteraction = new SPAInteraction(null, ajaxurl);
    public deltaDates : DeltaDate[] = [];
    public selectedButton : TimelineButton = 'week';
    private chart : any;
    public totalBrukereSelected : number = 0;

    public components = [
        BoxStatistikk,
        BoxStatistikkEnkel
    ];

    // Opprett nettsiden
    init() : void {
        // Get data via ajax
        if(!this.initialized) {
            this.initialized = true;
            this.fetchData();
        }
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
        this.loadingChart = true;

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
        this.loading = false;
        
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
        
        this.loadingChart = false;
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
