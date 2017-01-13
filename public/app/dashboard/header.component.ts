import {Component} from "@angular/core";
import {Title} from "@angular/platform-browser";
import {SignInComponent} from "../core/authorization/component/signIn.component";
import {FormBuilder} from "@angular/forms";
import {Http} from "@angular/http";
import {Router} from "@angular/router";
import {UserService} from "../core/authorization/service/user.service";

@Component({
    selector: 'cg-header',
    templateUrl: '/templates/dashboard/header.html',
    styleUrls: ['css/header.css'],
    providers: [UserService]
})
export class HeaderComponent{
    constructor(
        private user: UserService,
        private http: Http,
        private router: Router
    ){
    }

    logout() {
        this.http
            .post('/api/auth/logout', [])
            .subscribe(response => {
                this.router.navigate(['/']);
                this.user.isAuthorized();
            }, response => {
                console.log(response);
            })
    }
}