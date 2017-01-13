import {Http} from "@angular/http";
import {Injectable} from "@angular/core";
import 'rxjs/add/operator/map';
import {Subject} from "rxjs/Subject";

@Injectable()
export class DiscourseService {
    private baseRoute: string = '/api/discourses';

    private reloadDiscourseSource = new Subject<string>();

    reloadDiscourse$ = this.reloadDiscourseSource.asObservable();

    // Service message commands
    reloadDiscourse(discourse: string) {
        this.reloadDiscourseSource.next(discourse);
    }


    constructor( private http: Http){}

    list() {
        return this.http.get(this.baseRoute);
    }

    details(id) {
        return this.http.get(this.baseRoute+'/'+id);
    }

    log(id) {
        return this.http.get(this.baseRoute+'/'+id+'/log');
    }

    complete(id) {
        return this.http.put(`${this.baseRoute}/assignments/${id}/complete`, {});
    }

    confirm(id) {
        return this.http.put(`${this.baseRoute}/assignments/${id}/confirm`, {});
    }

    cancel(id) {
        return this.http.put(`${this.baseRoute}/assignments/${id}/cancel`, {});
    }

    assign(id: number, speakerId: number, speechId: number) {
        console.log(id, speakerId, speechId);
        return this.http.put(`${this.baseRoute}/assignments/create`, {
            discourseId: id,
            speakerId: speakerId,
            speechId: speechId
        });
    }

    deleteComment(id, commentId) {
        return this.http.delete(`${this.baseRoute}/${id}/log/${commentId}`);
    }
}