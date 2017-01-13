<?php
/**
 * Created by PhpStorm.
 * User: dmitricercel
 * Date: 14.11.16
 * Time: 21:40
 */
Route::group(['middleware' => 'web'], function () {
    Route::get('speakers/debtors', 'SpeakerController@debtors');
    Route::get('speakers/favorites', 'SpeakerController@favorites');

    Route::resource('speakers', 'SpeakerController');
});