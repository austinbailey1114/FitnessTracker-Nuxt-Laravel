<?php

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LogoutController@logout');

//bodyweights
Route::get('/bodyweights/{id}', 'BodyweightController@getBodyweight');
Route::post('/bodyweight', 'BodyweightController@postBodyweight');
Route::post('/deleteBodyweight', 'BodyweightController@deleteBodyweight');
//food goals
Route::post('/foodGoals', 'GoalsController@postFoodGoals');
Route::get('/foodGoals/{id}', 'GoalsController@getFoodGoals');

Route::get('/me', 'Auth\MeController@me');
