import Vue from 'vue';

export var TestComponent = Vue.component('feedback-meldpaa', { 
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
        plusOne : () => {
            this.value++;
        }
    },
    components : {
        
    },
    template: /*html*/`
        <h1>hello from vue: #{value}</h1>
        <button @click="plusOne()">Plus one</button>   
    `
})

