import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import {HttpModule} from "@angular/http";
import 'rxjs/Rx';
import {MaterialModule} from "@angular/material";
import {ReactiveFormsModule, FormsModule} from "@angular/forms";
import { RouterModule, Routes } from '@angular/router';
import {IndexComponent} from "./index.component";
import {CongregationModule} from "./congregation/congregation.module";
import {SpeakersModule} from "./congregation/modules/speakers/speakers.module";

const appRoutes: Routes = [];

@NgModule({
    imports: [
        BrowserModule,
        ReactiveFormsModule,
        FormsModule,
        HttpModule,
        MaterialModule.forRoot(),
        RouterModule.forRoot(appRoutes),
        CongregationModule,
        SpeakersModule
    ],
    declarations: [
        IndexComponent
    ],
    bootstrap: [
        IndexComponent
    ]
})
export class IndexModule { }
