<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div class="box-statistikk">
        <div class="info">
            <p :class="{'phantom-loading' : loading}" class="title">{{ title }}</p>
            <h3 :class="{'phantom-loading' : loading}" class="value">{{ antall }}</h3>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import { SPAInteraction } from 'ukm-spa/SPAInteraction';


declare var ajaxurl: string; // Kommer fra global

@Component
export default class BoxStatistikkEnkel extends Vue {
    @Prop() subaction! : string;
    @Prop() title! : string;

    private spaInteraction = new SPAInteraction(null, ajaxurl);
    public antall : number = 0;
    public loading = true;

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

        this.loading = false;

        return response;
    }
}

Vue.component('box-statistikk-enkel', BoxStatistikkEnkel);

</script>

<style>

</style>
