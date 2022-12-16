import { Director } from 'ukm-spa/Director';
import {openDeltaStats} from './delta';
import { openArrangorssysStats } from './arrangorssystem';

var director = new Director();
(<any>window).director = director;

var page = director.getParam('pageSPA');

console.log(page);

if(!page) {
    page = 'pageAppDelta';
}


director.addEventListener('openPage', function(obj : any) {
    if(obj.id == 'pageAppDelta') {
        openDeltaStats();
    }
    else if (obj.id == 'pageAppArrang') {
        openArrangorssysStats();
    }
    
    console.log('aaa');
    jQuery(".menu-items button.menu-item").removeClass('active');
    jQuery(".menu-item."+ obj.id).addClass('active');
    console.log(".menu-item."+ obj.id);
})

director.openPage(page);



