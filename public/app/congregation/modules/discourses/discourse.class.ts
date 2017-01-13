import * as moment from 'moment/moment';
import {DiscourseService} from "./discourse.service";
import {Injectable} from "@angular/core";

@Injectable()
export class Discourse {
    constructor( public data: any, private service: DiscourseService ) {}

    getTime() {
        return this.data.time;
    }

    getMoment() {
        return moment(this.data.time);
    }

    getYear() {
        let year: Number;
        year = parseInt(moment(this.data.time).format('Y'));

        return year;
    }

    getHistory() {
        return this.data.commentaries;
    }

    getMonth() {
        let month: String;
        month = moment(this.data.time).format('MMMM');

        return month;
    }

    getMonthNumber() {
        let month: Number;
        month = parseInt(moment(this.data.time).format('M'));

        return month;
    }

    getId() {
        return this.data.id;
    }

    getSpeaker() {
        return this.data.assignment.speaker;
    }

    getSpeech() {
        return this.data.assignment.speech;
    }

    isAssigned() {
        return this.data.assignment;
    }

    getAssignment() {
        return this.data.assignment;
    }

    isConfirmed() {
        return this.getAssignment().conditions.isConfirmed;
    }

    isPreset() {
        return this.getAssignment().conditions.isPreset;
    }

    isMoved() {
        return this.getAssignment().conditions.isMoved;
    }

    isCompleted() {
        return this.getAssignment().conditions.isCompleted;
    }

    isCanceled() {
        return this.getAssignment().conditions.isCanceled;
    }

    complete() {
        this.service.complete(this.getAssignment().id).subscribe(response => {
            this.service.reloadDiscourse('complete');
        });
    }

    confirm() {
        this.service.confirm(this.getAssignment().id).subscribe(response => {
            this.service.reloadDiscourse('confirm');
        });
    }

    cancel() {
        this.service.cancel(this.getAssignment().id).subscribe(response => {
            this.service.reloadDiscourse('cancel');
        });
    }

    assign(speakerId: number, speechId: number) {
        this.service.assign(this.data.id, speakerId, speechId).subscribe(response => {

        });
    }

    deleteComment(commentId) {
        this.service.deleteComment(this.getId(), commentId).subscribe(response => {
            this.service.reloadDiscourse('deleteCommentcd');
        });
    }
}