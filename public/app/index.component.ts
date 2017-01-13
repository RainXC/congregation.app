/**
 * Created by dmitricercel on 15.11.16.
 */
import { Component } from '@angular/core';
import {DiscourseHistoryService} from "./congregation/modules/discourses/history/discourseHistory.service";

@Component({
    selector: 'index-app',
    template: `<router-outlet></router-outlet>`,
    providers: [DiscourseHistoryService]
})
export class IndexComponent {}