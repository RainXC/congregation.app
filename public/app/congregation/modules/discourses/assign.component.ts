/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component, OnInit, OnDestroy, ElementRef, Renderer, AfterViewInit, ViewChild} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {ActivatedRoute, Router} from "@angular/router";
import {DiscourseService} from "./discourse.service";
import {Discourse} from "./discourse.class";
import {Location} from '@angular/common';
import {DiscourseHistoryComponent} from "./history/discourseHistory.component";
import {DiscourseHistoryService} from "./history/discourseHistory.service";
import {Subscription} from "rxjs";
import {Http} from "@angular/http";
import {SpeakerService} from "../speakers/speaker.service";
import {SpeechService} from "../speeches/speech.service";

@Component({
    selector: 'cg-assign',
    templateUrl: '/templates/congregation/discourses/assign.html',
    providers: [ Title, DiscourseService, SpeakerService, SpeechService ],
    styleUrls: ['css/assign.css', 'css/discourse.css'],
})
export class AssignComponent implements OnDestroy, OnInit, AfterViewInit {
    @ViewChild('searchSpeakers') input: ElementRef;
    @ViewChild('speechInput') speechInput: ElementRef;
    public id: number;
    public discourse: Discourse;
    public nextDisc: Discourse;
    public prevDisc: Discourse;
    public subscription: Subscription;
    public speakerName: string;
    public speakers: Array<any>;
    public debtors: Array<any>;
    public favorites: Array<any>;
    public speechesFound: Array<any>;
    public current: string;
    public speakerSelected: any;
    public speechSelected: any;
    public searchSpeech: string;
    public nextTwo: Array<any> = [];
    public prevTwo: Array<any> = [];


    constructor(
        private title: Title,
        private route: ActivatedRoute,
        private router: Router,
        private discourseService: DiscourseService,
        private speakerService: SpeakerService,
        private speechService: SpeechService,
        private renderer: Renderer
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

    ngAfterViewInit() {
        this.renderer.invokeElementMethod(this.input.nativeElement, 'focus');
    }

    ngOnInit() {
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
                if ( result.nextTwo ) {
                    this.nextTwo = result.nextTwo;
                }
                if ( result.prevTwo ) {
                    this.prevTwo = result.prevTwo;
                }
                this.loadFavorites();
                this.loadDebtors();

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
        this.speakerService.debtors(this.discourse.getTime()).subscribe(response => {
            this.debtors = response.json();
        }, response => {
            if ( response.status == 401 ) {
                this.router.navigate(['unauthorized']);
            }
        });
    }

    loadFavorites() {
        this.speakerService.favorites(this.discourse.getTime()).subscribe(response => {
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
        // this.renderer.invokeElementMethod(this.speechInput.nativeElement, 'focus');

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
        this.discourseService.assign(this.id, this.speakerSelected.speaker.id, this.speechSelected.speech.id).subscribe(response => {
            this.back();
        });

        return true;
    }

    cancelAssignment() {
        this.speechSelected = null;

        return true;
    }

    searchSpeechRun() {
        if ( this.searchSpeech.length > 0 ) {
            this.speechService.search(this.searchSpeech).subscribe(response => {
                this.speechesFound = response.json();
                console.log(this.speechesFound);
            }, response => {
                if (response.status == 401) {
                    this.router.navigate(['unauthorized']);
                }
            });
        } else {
            this.speechesFound = [];
        }

    }
}