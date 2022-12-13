<template>
    <div>
        <!-- Phantom loading -->
        <div v-show="loading">
            <table class="table ukm-vue-table-row">
                <thead>
                    <tr>
                        <th v-for="index in 5" :key="index"><span class="phantom-loading">---------</span></th>
                    </tr>
                </thead>
                <tbody>
                     <tr v-for="index in Math.floor((Math.random() * 15) + 5)" :key="index">
                        <td v-for="innerIndex in 5" :key="innerIndex"><span class="phantom-loading">-----</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="removed-keys">
            <button v-for="key in removedKeys" @click="addKey(key)" class="key ukm-botton-style correct-button">
                <span>{{ key.navn }}</span>
            </button>
        </div>
        <table v-show="!loading" class="table ukm-vue-table-row">
            <thead>
                <tr>
                    <th v-for="key in keys" scope="col">
                        <div class="inner-div">
                            <button @click="sortBy(key)" class="sort-button">
                                <span class="title">{{ key.navn }}</span>
                                <div class="indicators">
                                    <div>
                                        <svg :class="{'not-active' : currentSort == key.navn && ascSort}" class="sort-indicator" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 6 19 1" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m6.293 13.293 1.414 1.414L12 10.414l4.293 4.293 1.414-1.414L12 7.586z"></path></svg>
                                    </div>
                                    <div>
                                        <svg :class="{'not-active' : currentSort == key.navn && !ascSort}" class="sort-indicator" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 7 19 18" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M16.293 9.293 12 13.586 7.707 9.293l-1.414 1.414L12 16.414l5.707-5.707z"></path></svg>
                                    </div>
                                </div>
                            </button>
                            <button class="remove-row ukm-botton-style not-correct-button" @click="removeRow(key)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="3 4 19 18" style="fill: #272727"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in getItems()">
                    <td v-for="item in value">{{ item }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class TableKomponent extends Vue {
    @Prop() keys!: {navn : string, method : string}[];
    @Prop() values!: any[];
    @Prop() loading!: boolean;

    public removedKeys : {navn : string, method : string}[] = [];

    public currentSort : string = '';
    public ascSort : boolean = true;

    public sortBy(key : {navn : string, method : string}) {
        this.currentSort = key.navn;

        this.values.sort((a, b) => {return a[key.method]() > b[key.method]() ? 1 : (a[key.method]() < b[key.method]() ? -1 : 0)})

        if(this.ascSort) {
            this.values.reverse();
        }

        this.ascSort = !this.ascSort
    }

    // Delete row
    public removeRow(key : {navn : string, method : string}) {           
        for(var i = 0; i < this.keys.length; i++) {
            if(this.keys[i].navn == key.navn) { 
                this.removedKeys.push(this.keys[i]);
                this.keys.splice(i, 1);
            }
        }

    }

    public addKey(key : {navn : string, method : string}) {
        for(var i = 0; i < this.removedKeys.length; i++) {
            if(this.removedKeys[i].navn == key.navn) { 
                this.keys.push(key);
                this.removedKeys.splice(i, 1);

            }
        }
    }

    // Get all items from the classes sendt as array on values
    public getItems() {
        var items = [];
        for(var value of this.values) { 
            var item = []          
            for(var key of this.keys) {
                item.push(value[key.method]());
            }
            items.push(item);
        }

        return items;
    }
}

Vue.component('table-komponent', TableKomponent);
</script>

<style>
    
    .ukm-vue-table-row thead tr th .inner-div {
        display: flex;
    }
    .ukm-vue-table-row thead tr th .inner-div .indicators div {
        display: flex;
    }
    .ukm-vue-table-row thead tr th .inner-div .indicators div svg.sort-indicator.not-active{
        fill: #bebebe !important;
    }
        
    .ukm-vue-table-row thead tr th button.remove-row {
        visibility: hidden;
        border-radius: 25px;
        width: 25px;
        height: 25px;
        border: solid 1px #bebebe;
        margin: auto;
        margin-left: -5px;
        padding: 4px 3px;
        border: solid 1px #00000040 !important;
        background: #fff !important;
    }
    .ukm-vue-table-row thead tr th:hover button.remove-row {
        visibility: visible;
    }
    /* .sort-indicator.asc {
        transform: rotate(180deg);
    } */
    .ukm-vue-table-row thead tr th .sort-button {
        border: none;
        font-size: 14px;
        font-weight: 400;
        padding: 10px 20px;
        background: #0000;
        margin-left: -20px;
        display: flex;
    }
    .ukm-vue-table-row thead tr th .sort-button .title {
        margin: auto;
        margin-right: 5px;
    }
    .removed-keys {
        display: flex;
    }    
    .removed-keys button.key {

    }
</style>
