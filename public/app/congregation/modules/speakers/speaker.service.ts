import {Http} from "@angular/http";
import {Injectable} from "@angular/core";
import 'rxjs/add/operator/map';

@Injectable()
export class SpeakerService {
    constructor( private http: Http){}

    list() {
        return this.http.get('/api/speakers');
    }

    search(search) {
        return this.http.get('/api/speakers?search='+search);
    }

    favorites() {
        return this.http.get('/api/speakers/favorites');
    }

    debtors() {
        return this.http.get('/api/speakers/debtors');
    }

    long() {
        return this.http.get('/api/speakers/long');
    }
}