import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {DiscourseComponent} from "./discourse.component";
import {DiscoursesComponent} from "./discourses.component";
import {DiscoursesCalendarComponent} from "./calendar.component";
import {AssignComponent} from "./assign.component";

const routes: Routes = [
    { path: 'discourses',  component: DiscoursesCalendarComponent },
    { path: 'discourses/calendar',  component: DiscoursesCalendarComponent },
    { path: 'discourses/:id',  component: DiscourseComponent },
    { path: 'discourses/:id/assign',  component: AssignComponent },
];

@NgModule({
    imports: [
        RouterModule.forChild(routes)
    ],
    exports: [
        RouterModule
    ]
})
export class DiscoursesRoutesModule { }