import Vue from "vue";
import TilbakemeldingerKomponent from "./components/delta/Tilbakemeldinger.vue";
import ArrangementerKomponent from './components/arrangsys/Arrangementer.vue';
import { SPAInteraction } from 'ukm-spa/SPAInteraction';
import { Director } from 'ukm-spa/Director';

export function openArrangorssysStats() {
    new Vue({
        el: "#vueArrang",
        data: { 
            name: "World",
            activeTab : 'arrangementer'
        },
        
        components: {
            TilbakemeldingerKomponent,
            ArrangementerKomponent
        },
    
        mounted : function() {
            this.openTab(this.activeTab);
            // console.log(SPAInteraction);
            // console.log(Director);
        },
    
        methods : {
            // Open tab
            openTab: function(tabRef : string) : void {
                this.activeTab = tabRef;
                var tilbakemeldinger = (<ArrangementerKomponent>this.$refs[tabRef]);
                tilbakemeldinger.init();
            }
        },
    
        template: /*html*/`
        <div>    
            <div>
                <div class="tab-items">

                    <div class="tab-item">
                        <button :class="{'active' : activeTab == 'arrangementer'}" @click="openTab('arrangementer');">Arrangementer</button>
                    </div>

                    <div class="tab-item">
                        <button :class="{'active' : activeTab == 'tilbakemeldinger'}" @click="openTab('tilbakemeldinger');">Tilbakemeldinger</button>
                    </div>
                </div>
    
                
                <div class="tab-content tabs">
                    <div v-show="activeTab == 'tilbakemeldinger'">
                        <tilbakemeldinger-komponent ref="tilbakemeldinger" :name="name" :initialEnthusiasm="5" />
                    </div>
    
                    <div v-show="activeTab == 'arrangementer'">
                        <arrangementer-komponent ref="arrangementer" :name="name" :initialEnthusiasm="5" />
                    </div>
                </div>
            </div>
    
        </div>
    
        `
    });
}
