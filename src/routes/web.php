<?php

Route::group(['namespace' => 'Androidbeet\Nightbot\Http\Controllers'], function () {

    Route::get('nightbot/auth', 'NightbotController@showAuthPage')
        ->name('nightbot.auth');

    Route::get('nightbot/callback', 'NightbotController@catchCallback')
        ->name('nightbot.callback');

    Route::get('nightbot/twitch/success', 'NightbotController@showTwitchSuccessPage')
        ->name('nightbot.twitch.success');

});

