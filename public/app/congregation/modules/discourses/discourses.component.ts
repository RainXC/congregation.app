/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {DiscourseService} from "./discourse.service";
import {Router} from "@angular/router";
import {UserService} from "../../../core/authorization/service/user.service";
import {Discourse} from "./discourse.class";

@Component({
    selector: 'cg-discourses',
    templateUrl: '/templates/congregation/discourses/discourses.html',
    providers: [ Title, DiscourseService, UserService ],
    styleUrls: ['css/timeline.css']
})
export class DiscoursesComponent {
    public discourses: Array<any> = [];

    constructor(
        private title: Title,
        private discourseService: DiscourseService,
        private router: Router,
        private user: UserService
    ) {
        title.setTitle('Congregation App. Discourses list');
        this.loadDiscourses();
    }

    loadDiscourses(){
        this.discourseService
            .list()
            .subscribe(response => {
                let that = this;
                this.discourses = response.json();

                this.discourses.filter(function (discourse, index, array) {
                    that.discourses[index] = Object.assign(discourse, {
                        'object' : new Discourse(discourse, this.discourseService)
                    });
                });
                console.log(that.discourses);

            }, response => {
                if ( response.status == 401 ) {
                    this.router.navigate(['unauthorized']);
                }
            });
    }
}