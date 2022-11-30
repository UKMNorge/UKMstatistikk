import Vue from "vue";
import TilbakemeldingerKomponent from "./components/Tilbakemeldinger.vue";
import DeltaBrukKomponent from "./components/DeltaBruk.vue";
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Director } from 'ukm-spa/Director';

let v = new Vue({
    el: "#app",
    data: { 
        name: "World",
        activeTab : 'deltabruk'
    },
    
    components: {
        TilbakemeldingerKomponent,
        DeltaBrukKomponent
    },

    mounted : function() {
        this.openTab(this.activeTab);
        console.log(SPAInteraction);
        console.log(Director);
    },

    methods : {
        // Open tab
        openTab: function(tabRef : string) : void {
            this.activeTab = tabRef;
            var tilbakemeldinger = (<TilbakemeldingerKomponent>this.$refs[tabRef]);
            tilbakemeldinger.init();
        }
    },

    template: /*html*/`
    <div>    
        <div>
            <div class="tab-items">
                <div class="tab-item">
                    <button :class="{'active' : activeTab == 'deltabruk'}" @click="openTab('deltabruk');">Bruk</button>
                </div>
                <div class="tab-item">
                    <button :class="{'active' : activeTab == 'tilbakemeldinger'}" @click="openTab('tilbakemeldinger');">Tilbakemeldinger</button>
                </div>
            </div>

            
            <div class="tab-content tabs">
                <div v-show="activeTab == 'tilbakemeldinger'">
                    <tilbakemeldinger-komponent ref="tilbakemeldinger" :name="name" :initialEnthusiasm="5" />
                </div>

                <div v-show="activeTab == 'deltabruk'">
                    <delta-bruk-komponent ref="deltabruk" :name="name" :initialEnthusiasm="5" />
                </div>
            </div>
        </div>

    </div>

    `
});
