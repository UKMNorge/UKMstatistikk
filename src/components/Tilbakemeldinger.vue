<!-- This is an alternative way to define the Hello component using decorators -->
<template>
    <div>
        <div v-if="initialized">
            <div>
                87%
            </div>
        </div>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import TabInterface from '../interfaces/tabInterface'


@Component
export default class TilbakemeldingerKomponent extends Vue implements TabInterface {
    @Prop() name!: string;
    @Prop() initialEnthusiasm!: number;

    enthusiasm = this.initialEnthusiasm;
    public initialized : boolean = false;

    // Opprett nettsiden
    init() : void {
        // Get data via ajax
        this.initialized = true;
    }

    myMethod() : void {
        
    }
}
</script>

<style>

</style>
