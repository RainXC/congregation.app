<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 14.11.16
 * Time: 21:40
 */

Route::group(['middleware' => 'web'], function () {
    Route::get('discourses/calendar', 'DiscourseController@calendar');
    Route::put('discourses/assignments/{assignmentId}/confirm', 'DiscourseAssignmentsController@confirm');
    Route::put('discourses/assignments/{assignmentId}/complete', 'DiscourseAssignmentsController@complete');
    Route::put('discourses/assignments/{assignmentId}/cancel', 'DiscourseAssignmentsController@cancel');
    Route::put('discourses/assignments/create', 'DiscourseAssignmentsController@create');

    Route::resource('discourses/{discourseId}/log', 'DiscourseHistoryController');
    Route::resource('discourses', 'DiscourseController');
});