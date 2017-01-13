/**
 * Created by dmitricercel on 31.12.16.
 */
import {Component, Input, OnInit, OnChanges, Output, OnDestroy} from "@angular/core";
import {Router, ActivatedRoute} from "@angular/router";
import {Http} from "@angular/http";
import {FormBuilder, Validators} from "@angular/forms";
import {Discourse} from "../discourse.class";
import {DiscourseHistoryComponent} from "./discourseHistory.component";
import {EventEmitter} from "events";
import {DiscourseHistoryService} from "./discourseHistory.service";
import {Subscription, Observable} from "rxjs/Rx";

class Commentary {
    discourseId: number;
    text: string;
}

@Component({
    selector: 'cg-discourse-commentary',
    templateUrl: '/templates/congregation/discourses/history/commentary.html',
    styleUrls: ['css/commentary.css']
})
export class DiscourseCommentary implements OnInit, OnChanges {
    @Input() discourse: Discourse;

    public commentaryForm = this.fb.group({
        text: ["", Validators.required]
    });
    public data: Commentary = new Commentary;
    public error: Boolean = false;
    public submitted: boolean;
    public profile: any;
    public stored: boolean = false;
    public observable: any;

    constructor(
        private fb: FormBuilder,
        private http: Http,
        private router: Router,
        private historyService: DiscourseHistoryService
    ) {}

    ngOnInit() {
        this.data.discourseId = this.discourse.getId();
    }

    ngOnChanges() {
        this.data.discourseId = this.discourse.getId();
    }

    store(event) {
        if ( this.commentaryForm.valid ) {
            this.http
                .post('/api/discourses/'+this.data.discourseId+'/log', this.data)
                .subscribe(response => {
                    this.historyService.commentCreated('comment');
                    this.refreshForm();
                }, response => {
                    if ( response.status == 401 ) {
                        this.error = true;
                    } else {
                        console.log(response);
                    }
                });
        }
        this.submitted = true;
    }

    refreshForm() {
        this.data.text  = '';
        this.stored     = true;
        this.submitted  = false;
        this.observable = new Observable(observer => {
            setTimeout(() => {
                observer.next(21);
            },2000);
        });

        let subscriber = this.observable.subscribe(
            value => this.stored = false
        );
    }
}