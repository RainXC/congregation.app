/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component, Input, Output, EventEmitter, OnInit, OnChanges, OnDestroy} from '@angular/core';
import {DiscourseService} from "../discourse.service";
import {Discourse} from "../discourse.class";
import {DiscourseHistoryService} from "./discourseHistory.service";
import {Subscription} from "rxjs";

@Component({
    selector: 'cg-discourse-history',
    templateUrl: '/templates/congregation/discourses/history/discourseHistory.html',
    styleUrls: ['css/discourseHistory.css'],
    providers: [ DiscourseService ]
})
export class DiscourseHistoryComponent implements OnInit, OnChanges, OnDestroy {
    @Input() discourseId: number;
    @Input() discourse: Discourse;
    subscription: Subscription;

    private history: Array<any>;

    constructor( private discoursesService: DiscourseService, private historyService: DiscourseHistoryService) {

    }

    ngOnInit() {
        this.subscription = this.historyService.commentCreated$.subscribe(
            comment => {
                console.log(`${comment} has been created`);
                this.loadLog(this.discourseId||this.discourse.getId())
            });
        this.takeLog();
    }

    ngOnChanges() {
        this.takeLog();
    }

    ngOnDestroy(){
        this.subscription.unsubscribe();
    }

    takeLog() {
        if ( this.discourseId ) {
            this.loadLog(this.discourseId);
        }
        if ( this.discourse ) {
            this.history = this.discourse.getHistory();
        }
    }

    loadLog(id) {
        this.discoursesService
            .log(id)
            .subscribe(response => {
                this.history = response.json();
            });
    }
}