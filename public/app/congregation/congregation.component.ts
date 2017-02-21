/**
 * Created by dmitricercel on 15.11.16.
 */
import {Component, OnInit} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {UserService} from "../core/authorization/service/user.service";

@Component({
    selector: 'congregation-app',
    templateUrl: '/templates/congregation/congregation.component.html',
    providers: [ Title, UserService]
})
export class CongregationComponent implements OnInit {
    private loginFormDisplays: boolean = false;
    private registerFormDisplays: boolean = false;

    constructor(
        public title: Title,
        private user: UserService,
    ) {
        title.setTitle('Congregation App. It helps you to bill info');
    }

    isAuthorized() {
        return this.user.authorized;
    }

    showLoginForm() {
        this.loginFormDisplays = true;
    }

    hideLoginForm() {
        this.loginFormDisplays = false;
    }

    showRegisterForm() {
        this.registerFormDisplays = true;
    }

    hideRegisterForm() {
        this.registerFormDisplays = false;
    }

    ngOnInit() {
        window.initLanding();
        var wow = new WOW(
            {
                boxClass:     'wow',      // animated element css class (default is wow)
                animateClass: 'animated', // animation css class (default is animated)
                offset:       0,          // distance to the element when triggering the animation (default is 0)
                mobile:       true,       // trigger animations on mobile devices (default is true)
                live:         true,       // act on asynchronously loaded content (default is true)
                callback:     function(box) {
                    // the callback is fired every time an animation is started
                    // the argument that is passed in is the DOM node being animated
                },
                scrollContainer: null // optional scroll container selector, otherwise use window
            }
        );
        wow.init();
    }
}
