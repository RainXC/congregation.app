/**
 * Created by dmitricercel on 31.12.16.
 */
import {Injectable} from "@angular/core";
import {Subject} from "rxjs/Subject";

@Injectable()
export class DiscourseHistoryService {
    private commentCreatedSource = new Subject<string>();

    commentCreated$ = this.commentCreatedSource.asObservable();

    // Service message commands
    commentCreated(comment: string) {
        this.commentCreatedSource.next(comment);
    }

}