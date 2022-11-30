<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div>
        <div v-if="initialized">
            <div>
                <div v-for="tbMelding in tilbakemeldinger" v-bind:key="tbMelding" :key="tbMelding.id">
                    <p>{{ tbMelding.sporsmaal }}</p>
                    <p>{{ tbMelding.svar }}</p>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface';
import { SPAInteraction } from 'ukm-spa/SPAInteraction';



@Component
export default class TilbakemeldingerKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;
    private spaInteraction = new SPAInteraction(null, 'https://ukm.dev/2023-deatnu-tana-deatnu-tana-sorelv/wp-admin/');
    public tilbakemeldinger : Array<any> = [];

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
            console.warn(tilbakemelding);
            this.tilbakemeldinger.push(tilbakemelding);
        }

        return tilbakemeldinger;
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
