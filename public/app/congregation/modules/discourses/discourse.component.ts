/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component, OnInit, OnDestroy} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {ActivatedRoute, Router} from "@angular/router";
import {DiscourseService} from "./discourse.service";
import {Discourse} from "./discourse.class";
import {Location} from '@angular/common';
import {DiscourseHistoryComponent} from "./history/discourseHistory.component";
import {ViewChild} from "@angular/core/src/metadata/di";
import {DiscourseHistoryService} from "./history/discourseHistory.service";
import {Subscription} from "rxjs";

@Component({
    selector: 'cg-discourse',
    templateUrl: '/templates/congregation/discourses/discourse.html',
    providers: [ Title, DiscourseService ]
})
export class DiscourseComponent implements OnDestroy {
    public id: number;
    public discourse: Discourse;
    public nextDisc: Discourse;
    public prevDisc: Discourse;
    subscription: Subscription;

    constructor(
        private title: Title,
        private route: ActivatedRoute,
        private router: Router,
        private discourseService: DiscourseService
    ) {
        this.title.setTitle('Congregation App. Discourse details');
        this.route.params.subscribe(params => {
            this.id = +params['id'];
            this.loadDiscourse(this.id);
        });
        this.subscription = this.discourseService.reloadDiscourse$.subscribe(
            discourse => {
                console.log(`${discourse} should be reloaded`);
                this.loadDiscourse(this.id);
            });
    }

    loadDiscourse( id ) {
        this.discourseService
            .details(id)
            .subscribe(response => {
                let result = response.json();
                if ( result.discourse ) {
                    this.discourse = new Discourse(result.discourse, this.discourseService);
                }
                if ( result.prev ) {
                    this.prevDisc  = new Discourse(result.prev, this.discourseService);
                }
                if ( result.next ) {
                    this.nextDisc  = new Discourse(result.next, this.discourseService);
                }

            }, response => {
                if ( response.status == 401 ) {
                    this.router.navigate(['unauthorized']);
                }
            });
    }

    back() {
        this.router.navigate(['discourses/calendar']);
    }

    assign() {
        this.router.navigate([`discourses/${this.id}/assign`]);
    }

    prev() {
        this.router.navigate(['/discourses', this.prevDisc.getId()]);
    }

    next() {
        this.router.navigate(['/discourses', this.nextDisc.getId()]);
    }

    ngOnDestroy(){
        this.subscription.unsubscribe();
    }
}