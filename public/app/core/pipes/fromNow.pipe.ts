import {Pipe, PipeTransform} from "@angular/core";
import * as moment from 'moment/moment';

@Pipe({ name: 'fromNow' })
export class FromNowPipe implements PipeTransform {

    transform(value: any): string {
        if (value === undefined || value === null) return null;

        let date = moment(value);
        return date.fromNow();
    }

}