import { Director } from 'ukm-spa/Director';
import {openDeltaStats} from './delta';
import { openArrangorssysStats } from './arrangorssystem';

var director = new Director();
(<any>window).director = director;

var page = director.getParam('pageSPA');

if(!page) {
    page = 'pageAppDelta';
}


director.addEventListener('openPage', function(obj : any) {
    if(obj.id == 'pageAppDelta') {
        openDeltaStats();
    }
    else if (obj.id == 'pageAppArrang') {
        console.log('arrang');
    }
})

director.openPage(page);



