<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div v-if="initialized">
        <h4>Arrangementer</h4>
        <div class="box-statistikk-div">
            <box-statistikk ref="antall-brukere" :loading="loading" :title="'Antall arrangementer'" :labels="['Kommune', 'Fylke']" :chartkeys="['antall_kommune', 'antall_fylke']" :subaction="'getAntallArrangementer'"  />

            <!-- <box-statistikk-enkel ref="antall-brukere-facebook" :title="'Antall gjennom Facebook'" :subaction="'getTotalBrukereFbDelta'" /> -->
            <!-- <box-statistikk-enkel ref="antall-passord-bedt" :title="'Antall arrangementer i år'" :subaction="'getAntallArrangementer'" /> -->
            <!-- <box-statistikk-enkel ref="antall-passord-bedt" :title="'Antall arrangementer i fjor'" :subaction="'getAntalPass'" /> -->
        </div>
        <h4>Arrangementer gjennom årene</h4>
        <div>
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
    public sesongAntall : any[] = [];
    public selectedButton : TimelineButton = 'week';
    private chart : any;
    public totalBrukereSelected : number = 0;

    private festivaler : number[]= [];
    private workshoper : number[] = [];
    private sesonger : number[]= [];

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

    public async fetchData() {
        this.loadingChart = true;

        var data = {
            action: 'UKMstatistikk_ajax',
            subaction: 'getAntallArrangementerAar',
        };
        

        var response = await this.spaInteraction.runAjaxCall('/', 'POST', data);

        for (let key in response) {
            var res = response[key];
            // Sesonger
            this.sesonger.push(parseInt(key));
            // Monstring (festival)
            this.festivaler.push(parseInt(res.monstring ? res.monstring.antall : 0));
            // Arrangement (workshop)
            this.workshoper.push(parseInt(res.arrangement ? res.arrangement.antall : 0));
        }


        this.genererChart();
        this.loading = false;
        
        return response;
    }

    genererChart() : void {
        const data = {
            labels: this.sesonger,
            datasets: [
                {
                    label: 'Festivaler',
                    data: this.festivaler,
                    backgroundColor: '#60aa96',
                    stack: 'Stack 0',
                },
                {
                    label: 'Workshoper',
                    data: this.workshoper,
                    backgroundColor: '#ee6f58',
                    stack: 'Stack 0',
                },
            ]
        };

        this.chart = new Chart("deltaBrukereChart", {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Bar Chart - Stacked'
                },
                },
                responsive: true,
            }
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
