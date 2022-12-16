<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div v-if="initialized">
        <div class="col-xs-8">
           <table-komponent :loading="loading" :keys="tableKeys" :values="tilbakemeldinger"></table-komponent>
        </div>

        <div class="col-xs-4">
            <canvas id="tilbakemeldingerChart" style="width:100%;max-width:600px"></canvas>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../../interfaces/tabInterface';
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import Tilbakemelding from '../../objects/Tilbakemelding';
import TableKomponent from 'ukm-vue-komponenter';

declare var ajaxurl: string; // Kommer fra global


@Component
export default class TilbakemeldingerKomponent extends Vue implements TabInterface {
    @Prop() name!: string;

    public initialized : boolean = false;
    public loading : boolean = true;

    private spaInteraction = new SPAInteraction(null, ajaxurl);
    
    public tilbakemeldinger : Tilbakemelding[] = [];
    public tableKeys : {navn : string, method : string}[]= [];

    public components = [TableKomponent];

    // Opprett nettsiden
    init() : void {
        // Get data via ajax
        if(!this.initialized) {
            this.initialized = true;
            this.fetchData();
        }
    }

    private async fetchData() {
        var tilbakemeldinger = await this.getResponses('getTilbakemeldinger', {});

        this.tilbakemeldinger = [];
        var tb : Tilbakemelding|null = null;
        for(var tilbakemelding of tilbakemeldinger) {
            var innslagType = tilbakemelding.innslag_type ? tilbakemelding.innslag_type.name : '';

            tb = new Tilbakemelding(tilbakemelding.id, tilbakemelding.sporsmaal, tilbakemelding.svar, innslagType);
            this.tilbakemeldinger.push(tb);
        }

        if(tb) {
            this.tableKeys = tb.getKeysForTable();
        }

        this.loading = false;
        this.updateDoughnut();
        return tilbakemeldinger;
    }

    private updateDoughnut() {
        var countJa : number = this.tilbakemeldinger.filter(tb => tb.getSvar() == 'ja').length
        var countNei : number = this.tilbakemeldinger.length - countJa;

        var xValues = ["Ja", "Nei",];
        var yValues = [countJa, countNei];
        var barColors = ["#60aa96", "#ee6f58"];

        new Chart("tilbakemeldingerChart", {
            type: "doughnut",
            data: {
                labels: xValues,
                datasets: [{
                backgroundColor: barColors,
                data: yValues
                }]
            }
        });
    }

    private async getResponses(action : string, param_data : {}) {
        var url = new URL(window.location.href);

        var data = {
            action: 'UKMstatistikk_ajax',
            subaction: action,
            til: url.searchParams.get("til")
        };

        var responses = await this.spaInteraction.runAjaxCall('/', 'POST', data);

        return responses;
    }
}
</script>

<style>

</style>
