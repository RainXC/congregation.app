import {Component} from "@angular/core";
import {Title} from "@angular/platform-browser";
import {SignInComponent} from "../core/authorization/component/signIn.component";
import {FormBuilder} from "@angular/forms";
import {Http} from "@angular/http";
import {Router} from "@angular/router";
import {UserService} from "../core/authorization/service/user.service";
import * as moment from 'moment/moment';

@Component({
    selector: 'cg-footer',
    templateUrl: '/templates/dashboard/footer.html',
    styleUrls: ['css/footer.css']
})
export class FooterComponent{
    protected year: number;

    constructor(){
        this.year = moment().year();
    }
}