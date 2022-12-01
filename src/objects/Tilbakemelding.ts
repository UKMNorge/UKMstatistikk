// Representerer en tilbakemelding

export default class Tilbakemelding {
    private id : number;
    private sporsmaal : string;
    private svar : string;
    private innslagType : string;
    
    constructor(id : number, sporsmaal : string, svar : string, innslagType? : string) {
        this.id = id;
        this.sporsmaal = sporsmaal;
        this.svar = svar;
        this.innslagType = innslagType ? innslagType : '';
    }

    public getId() : number {
        return this.id;
    }

    public getSporsmaal() : string {
        return this.sporsmaal;
    }

    public getSvar() : string {
        return this.svar;
    }

    public getInnslagType() : string {
        return this.innslagType;
    }
}