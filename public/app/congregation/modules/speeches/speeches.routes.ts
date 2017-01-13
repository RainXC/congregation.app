import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {SpeechComponent} from "./speech.component";
import {SpeechesComponent} from "./speeches.component";

const routes: Routes = [
    { path: 'speeches',  component: SpeechesComponent },
    { path: 'speeches/:id',  component: SpeechComponent },
];

@NgModule({
    imports: [
        RouterModule.forChild(routes)
    ],
    exports: [
        RouterModule
    ]
})
export class SpeechesRoutesModule { }