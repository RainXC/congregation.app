/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {SpeechService} from "./speech.service";
import {Router} from "@angular/router";
import {SlimLoadingBarService} from 'ng2-slim-loading-bar';

@Component({
    selector: 'cg-speeches',
    templateUrl: '/templates/congregation/speeches/speeches.html',
    providers: [ Title, SpeechService ]
})
export class SpeechesComponent{
    speeches: Array<any>;
    pages: Array<number>  = [10, 20, 50, 100, 200];
    itemsPerPage: number = 10;

    constructor(
        private title: Title,
        private speechService: SpeechService,
        private router: Router,
        private slimLoadingBar: SlimLoadingBarService
    ) {
        title.setTitle('Congregation App. Speeches list');
        this.slimLoadingBar.start();
        this.loadSpeeches();
    }

    loadSpeeches(){
        this.speechService
            .list()
            .subscribe(response => {
                this.speeches = response.json();
                this.slimLoadingBar.complete();
            }, response => {
                if ( response.status == 401 ) {
                    this.router.navigate(['unauthorized']);
                }
            });
    }
}