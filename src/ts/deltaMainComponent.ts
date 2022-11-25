// import App from './App.vue'
// import { TestComponent } from "./testComponent";

// export default new Vue({
//     delimiters: ['#{', '}'], // For å bruke det på Twig
//     el: '#myVueObject',
//     data : function() {
//         return {
//             value: 0
//         }
//     },
//     mounted() {
//         console.log('mounted!');
//         console.log(jQuery('#myVueObject'));
//     },
//     methods : {
//         plusOne : function() {
//             this.value++;
//         }
//     },
//     components : {

//     },
//     template: /*html*/`
//         <div>
//             <h1>hello from vue MAIN: #{value}</h1>
//             <button>Plus one TEST</button>   
//             <button @click="plusOne()">Plus one</button>   
//         </div>
//     `
// })

export class DeltaMainComponent{
    private value : number = 123;
    public constructor() {
       console.log('aa');
    }
    
    public data() {
        return {
            value: 0
        }
    }

    mounted() {
        console.log('Yo Yo, mounted!');
    }
}

