import { TestComponent } from './ts/testVue';
import DeltaMainComponent from './ts/deltaMainComponent';


var dm = DeltaMainComponent;
interface IHomer {
    name(): String;
}


class Homer implements IHomer {
    name(){
        return 'Homer Simpson';
    }
}

const instance = new Homer();
console.log(instance.name());