/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component, Input} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {DiscourseService} from "./discourse.service";
import {Router} from "@angular/router";
import {UserService} from "../../../core/authorization/service/user.service";
import {DiscourseCalendarService} from "./calendar.service";
import * as moment from 'moment/moment';

@Component({
    selector: 'cg-discourses-calendar',
    templateUrl: '/templates/congregation/discourses/applecalendar.html',
    providers: [ Title, DiscourseCalendarService, UserService ],
    styleUrls: ['css/timeline.css', 'css/calendar.css']
})
export class DiscoursesCalendarComponent {
    @Input() from: any;
    @Input() to: any;

    public years: Array<any> = [];

    constructor(
        private title: Title,
        private calendarService: DiscourseCalendarService,
        private router: Router,
        private user: UserService
    ) {
        title.setTitle('Congregation App. Discourses list');
        this.loadDiscourses();
    }

    loadDiscourses(){
        this.calendarService
            .list(this.from, this.to)
            .subscribe(response => {
                let that = this;
                this.years = response.json();
                console.log(that.years);

            }, response => {
                if ( response.status == 401 ) {
                    this.router.navigate(['unauthorized']);
                }
            });
    }
}