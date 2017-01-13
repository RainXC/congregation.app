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
import {Http} from "@angular/http";
import {SpeakerService} from "../speakers/speaker.service";

@Component({
    selector: 'cg-assign',
    templateUrl: '/templates/congregation/discourses/assign.html',
    providers: [ Title, DiscourseService, SpeakerService ],
    styleUrls: ['css/assign.css'],
})
export class AssignComponent implements OnDestroy, OnInit {
    public id: number;
    public discourse: Discourse;
    public nextDisc: Discourse;
    public prevDisc: Discourse;
    public subscription: Subscription;
    public speakerName: string;
    public speakers: Array<any>;
    public debtors: Array<any>;
    public favorites: Array<any>;
    public current: string;
    public speakerSelected: any;
    public speechSelected: any;


    constructor(
        private title: Title,
        private route: ActivatedRoute,
        private router: Router,
        private discourseService: DiscourseService,
        private speakerService: SpeakerService
    ) {
        this.title.setTitle('Congregation App. Create assignment');
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

    ngOnInit() {
        this.loadFavorites();
        this.loadDebtors();
        this.setCurrentList('favorites');
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
        this.router.navigate([`discourses/${this.id}`]);
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

    reloadSpeakers() {
        if ( this.speakerName.length > 2 ) {
            this.setCurrentList('search');
            this.speakerService.search(this.speakerName).subscribe(response => {
                this.speakers = response.json();
                console.log(this.speakers);
            }, response => {
                if ( response.status == 401 ) {
                    this.router.navigate(['unauthorized']);
                }
            });
        } else {
            this.speakers = [];
        }
    }

    loadDebtors() {
        this.speakerService.debtors().subscribe(response => {
            this.debtors = response.json();
        }, response => {
            if ( response.status == 401 ) {
                this.router.navigate(['unauthorized']);
            }
        });
    }

    loadFavorites() {
        this.speakerService.favorites().subscribe(response => {
            this.favorites = response.json();
        }, response => {
            if ( response.status == 401 ) {
                this.router.navigate(['unauthorized']);
            }
        });
    }

    setCurrentList( current: string ) {
        this.current = current;
    }

    setSearch() {
        if ( this.speakers )
        this.setCurrentList('search');
    }

    isCurrentList( current: string ) {
        return this.current == current;
    }

    setSpeakerSelected(speaker) {
        this.speakerSelected = speaker;

        return true;
    }

    setSpeechSelected(speech) {
        this.speechSelected = speech;

        return true;
    }

    returnToSpeakers() {
        this.speakerSelected = null;

        return true;
    }

    returnToSpeakerSelected() {
        this.speechSelected = null;

        return true;
    }

    createAssignment() {
        this.discourseService.assign(this.id, this.speakerSelected.id, this.speechSelected.id).subscribe(response => {
            this.back();
        });

        return true;
    }

    cancelAssignment() {
        this.speechSelected = null;

        return true;
    }
}