// Representerer antall brukere i en dato

import TableItemInterface from "../interfaces/TableItemInterface";

export default class DeltaDate implements TableItemInterface {
    private date : Date;
    private total : number;
    
    constructor(date : string, total : number) {
        this.date = new Date(date);
        this.total = total;
    }

    public getDate() : Date {
        return this.date;
    }

    public getTotal() : number {
        return this.total;
    }

    public getDateString() : string {
        return this.date.getDate() + '.' + this.date.getMonth() + '.' + this.date.getFullYear();
    }

    public getHourString() : string {
        return this.date.getHours() + ':' + this.date.getMinutes();
    }

    public getKeysForTable() : {navn : string, method : string}[] {
        return [
            {navn : 'Dato', method :'getDate'},
            {navn : 'Antall', method : 'getTotal'},
        ];
    }
}