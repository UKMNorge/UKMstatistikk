"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.TestComponent = void 0;
var vue_1 = __importDefault(require("vue"));
exports.TestComponent = vue_1.default.component('feedback-meldpaa', {
    delimiters: ['#{', '}'],
    el: '#myVueObject',
    data: function () {
        return {
            value: 0
        };
    },
    mounted: function () {
        console.log('mounted!');
    },
    methods: {
        plusOne: function () {
            this.value++;
        }
    },
    components: {},
    template: /*html*/ "\n        <h1>hello from vue: #{value}</h1>\n        <button @click=\"plusOne()\">Plus one</button>   \n    "
});
