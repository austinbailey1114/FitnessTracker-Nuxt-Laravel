<?php

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LogoutController@logout');

Route::middleware(['auth:api'])->group(function() {
    //bodyweights
    Route::get('/bodyweights/{id}', 'BodyweightController@getBodyweight');
    Route::post('/bodyweight', 'BodyweightController@postBodyweight');
    Route::post('/deleteBodyweight', 'BodyweightController@deleteBodyweight');
    //food goals
    Route::post('/foodGoals', 'GoalsController@postFoodGoals');
    Route::get('/foodGoals/{id}', 'GoalsController@getFoodGoals');
    //foods
    Route::post('/food', 'FoodController@postFood');
    Route::get('/recentFoods/{user}', 'FoodController@recentFoods');
    Route::get('/totals/{user}', 'FoodController@getTotals');
    //lifts
    Route::post('/lift', 'LiftController@postLift');
    Route::get('/lifts/{id}', 'LiftController@getLift');
    Route::post('/deleteLift', 'LiftController@deleteLift');
    //lifttypes
    Route::get('/lifttypes/{id}', 'LifttypeController@getLifttypes');

    Route::get('/me', 'Auth\MeController@me');
});
