// import { createApp } from 'window';

const { createApp } = (<any>window).Vue



export default createApp({
    delimiters: ['#{', '}'], // For å bruke det på Twig
    el: '#myVueObject',
    data : function() {
        return {
            value: 0
        }
    },
    mounted() {
        console.log('mounted!');
    },
    methods : {
        plusOne : function() {
            console.log('plus one');
            this.value++;
        }
    },
    components : {

    },
    template: /*html*/`
        <div>
            <h1>hello from vue MAIN: #{value}</h1>
            <button>Plus one TEST</button>   
            <button @click="plusOne()">Plus one</button>   
        </div>
    `
}).mount('#myVueObject');