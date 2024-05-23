<?php

use App\Http\Controllers\Api\BlacklistController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\ProfileController;
use App\Models\Channel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $previousChannel = Cookie::get('randomChannel');

    // Fetch a random channel that is different from the previous one
    $randomChannelQuery = Channel::inRandomOrder();
    if ($previousChannel) {
        $randomChannelQuery->where('channel', '!=', $previousChannel);
    }
    $randomChannel = $randomChannelQuery->first();
    $randomChannelName = $randomChannel ? $randomChannel->channel : null;

    if ($randomChannelName) {
        Cookie::queue('randomChannel', $randomChannelName, 5); // Set cookie for 5 minutes
    }

    return view('welcome', ['randomChannel' => $randomChannelName]);
})->name('index');

// streamer dashboard
Route::get('streamers/blacklist', function () {
    return view('blacklist');
})->middleware(['auth', 'verified'])->name('streamers.blacklist');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// api
Route::post('api/channels/add', [ChannelController::class, 'addChannel']);
Route::post('api/channels/remove', [ChannelController::class, 'removeChannel']);
Route::post('api/blacklist', [BlacklistController::class, 'update']);

require __DIR__.'/auth.php';
require __DIR__.'/socialstream.php';
