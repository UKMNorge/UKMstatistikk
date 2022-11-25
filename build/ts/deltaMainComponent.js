"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var vue_1 = __importDefault(require("vue"));
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
var deltaMainComponent = /** @class */ (function (_super) {
    __extends(deltaMainComponent, _super);
    function deltaMainComponent() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.x = 'hello';
        return _this;
    }
    deltaMainComponent.prototype.data = function () {
        return {
            value: 0
        };
    };
    deltaMainComponent.prototype.mounted = function () {
        console.log('Yo Yo, mounted!');
    };
    return deltaMainComponent;
}(vue_1.default));
exports.default = deltaMainComponent;
