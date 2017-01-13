/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component} from '@angular/core';
import {Title} from "@angular/platform-browser";

@Component({
    selector: 'cg-speech',
    templateUrl: '/templates/congregation/speeches/speech.html',
    providers: [ Title ]
})
export class SpeechComponent {

    constructor(title: Title) {
        title.setTitle('Congregation App. Speech details');
    }

}