import {Http} from "@angular/http";
import {Injectable} from "@angular/core";
import 'rxjs/add/operator/map';

@Injectable()
export class DiscourseCalendarService {
    constructor( private http: Http){}

    list() {
        return this.http.get('/api/discourses/calendar');
    }


}