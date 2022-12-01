<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div>
        <div class="col-xs-8">
            <table v-if="initialized" class="table">
                <thead>
                    <tr>
                        <th scope="col">Spørsmål</th>
                        <th scope="col">Svar</th>
                        <th scope="col">Innslag Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tilbakemelding in tilbakemeldinger" v-bind:key="tilbakemelding" :key="tilbakemelding.getId()">
                        <td>{{ tilbakemelding.getSporsmaal() }}</td>
                        <td>{{ tilbakemelding.getSvar() }}</td>
                        <td>{{ tilbakemelding.getInnslagType() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-xs-4">
            <canvas id="tilbakemeldingerChart" style="width:100%;max-width:600px"></canvas>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface';
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Chart } from 'chart.js';
import Tilbakemelding from '../objects/Tilbakemelding';




@Component
export default class TilbakemeldingerKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, 'https://ukm.dev/2023-deatnu-tana-deatnu-tana-sorelv/wp-admin/');
    public tilbakemeldinger : Array<Tilbakemelding> = [];

    // Opprett nettsiden
    init() : void {
        // Get data via ajax
        this.initialized = true;
        this.fetchData();
    }

    private async fetchData() {
        var tilbakemeldinger = await this.getResponses('getTilbakemeldinger', {});
        tilbakemeldinger = JSON.parse(tilbakemeldinger);

        this.tilbakemeldinger = [];
        for(var tilbakemelding of tilbakemeldinger) {
            var innslagType = tilbakemelding.innslag_type ? tilbakemelding.innslag_type.name : '';

            this.tilbakemeldinger.push(new Tilbakemelding(tilbakemelding.id, tilbakemelding.sporsmaal, tilbakemelding.svar, innslagType));
        }

        this.updateDoughnut();
        return tilbakemeldinger;
    }

    private updateDoughnut() {
        var countJa : number = this.tilbakemeldinger.filter(tb => tb.getSvar() == 'ja').length
        var countNei : number = this.tilbakemeldinger.length - countJa;

        var xValues = ["Ja", "Nei",];
        var yValues = [countJa, countNei];
        var barColors = ["#00aba9", "#b91d47"];

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

        var responses = await this.spaInteraction.runAjaxCall('admin-ajax.php/', 'POST', data);

        return responses;
    }

    myMethod() : void {
        
    }
}
</script>

<style>

</style>
