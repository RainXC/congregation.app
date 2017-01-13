import {Pipe, PipeTransform} from "@angular/core";
import * as moment from 'moment/moment';

@Pipe({ name: 'smartdate' })
export class SmartDatePipe implements PipeTransform {

    transform(value: any): string {
        if (value === undefined || value === null) return null;

        let date = moment(value);
        if ( this.isWeekDiff(date) ) {
            return date.fromNow();
        }
        if ( this.isDaysDiff(date, 14) ) {
            return date.calendar();
        }

        return date.format('DD.MM.YYYY HH:mm:ss');
    }

    isWeekDiff( date ) {
        return this.isDaysDiff(date, 7);
    }

    isMonthDiff( date ) {
        return this.isDaysDiff(date, 30);
    }

    isDaysDiff( date, days ) {
        return moment().diff(date, 'days') <= days;
    }
}