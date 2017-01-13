import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import {HttpModule} from "@angular/http";
import 'rxjs/Rx';
import {MaterialModule} from "@angular/material";
import {ReactiveFormsModule, FormsModule} from "@angular/forms";
import { RouterModule, Routes } from '@angular/router';
import {IndexComponent} from "./index.component";
import {UnauthorizedComponent} from "./core/authorization/unautorized.component";
import {DashboardComponent} from "./dashboard/dashboard.component";
import {NotFoundComponent} from "./core/notFound.component";
import {HeaderComponent} from "./dashboard/header.component";
import {SpeakersRoutesModule} from "./congregation/modules/speakers/speakers.routes";
import {SpeakersComponent} from "./congregation/modules/speakers/speakers.component";
import {SpeakerComponent} from "./congregation/modules/speakers/speaker.component";
import {SpeechesRoutesModule} from "./congregation/modules/speeches/speeches.routes";
import {SpeechesComponent} from "./congregation/modules/speeches/speeches.component";
import {SpeechComponent} from "./congregation/modules/speeches/speech.component";
import {DiscoursesRoutesModule} from "./congregation/modules/discourses/discourses.routes";
import {DiscourseComponent} from "./congregation/modules/discourses/discourse.component";
import {DiscoursesComponent} from "./congregation/modules/discourses/discourses.component";
import {DiscoursesCalendarComponent} from "./congregation/modules/discourses/calendar.component";
import {SignInComponent} from "./core/authorization/component/signIn.component";
import {CommonModule} from "@angular/common";
import {CongregationRoutesModule} from "./congregation/congregation.routes";
import {CongregationComponent} from "./congregation/congregation.component";
import {FooterComponent} from "./dashboard/footer.component";
import {DiscourseHistoryComponent} from "./congregation/modules/discourses/history/discourseHistory.component";
import {FromNowPipe} from "./core/pipes/fromNow.pipe";
import {CalendarPipe} from "./core/pipes/calendar.pipe";
import {DatePipe} from "./core/pipes/date.pipe";
import {SmartDatePipe} from "./core/pipes/smartDate.pipe";
import {DiscourseCommentary} from "./congregation/modules/discourses/history/commentary.component";
import {AssignComponent} from "./congregation/modules/discourses/assign.component";
import {Ng2PaginationModule} from "ng2-pagination";

const appRoutes: Routes = [
    { path: 'unauthorized', component: UnauthorizedComponent },
    { path: 'dashboard', component: DashboardComponent },
    { path: '404', component: NotFoundComponent },

    { path: '**', redirectTo: '/404' }
];

@NgModule({
    declarations: [
        IndexComponent,
        UnauthorizedComponent,
        DashboardComponent,
        NotFoundComponent,
        HeaderComponent,
        FooterComponent,
        SpeakersComponent,
        SpeakerComponent,
        SpeechesComponent,
        SpeechComponent,
        AssignComponent,
        DiscourseComponent,
        DiscoursesComponent,
        DiscoursesCalendarComponent,
        DiscourseHistoryComponent,
        SignInComponent,
        CongregationComponent,
        DatePipe,
        SmartDatePipe,
        CalendarPipe,
        FromNowPipe,
        DiscourseCommentary
    ],
    imports: [
        CommonModule,
        BrowserModule,
        ReactiveFormsModule,
        FormsModule,
        HttpModule,
        MaterialModule.forRoot(),
        RouterModule.forRoot(appRoutes),
        SpeakersRoutesModule,
        SpeechesRoutesModule,
        DiscoursesRoutesModule,
        CongregationRoutesModule,
        Ng2PaginationModule
    ],
    bootstrap: [
        IndexComponent
    ]
})
export class IndexModule { }
