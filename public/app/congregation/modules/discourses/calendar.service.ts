import {Http} from "@angular/http";
import {Injectable} from "@angular/core";
import 'rxjs/add/operator/map';

@Injectable()
export class DiscourseCalendarService {
    constructor( private http: Http){}

    list(from: any, to: any) {
        return this.http.get(`/api/discourses/calendar?from=${from}&to=${to}`);
    }


}