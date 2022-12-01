// Representerer en tilbakemelding

class Tilbakemelding {
    private id : number;
    private sporsmaal : string;
    private svar : string;
    
    constructor(id : number, sporsmaal : string, svar : string) {
        this.id = id;
        this.sporsmaal = sporsmaal;
        this.svar = svar;
    }

    public getSporsmaal() : string {
        return this.sporsmaal;
    }

    public getSvar() : string {
        return this.svar;
    }
}