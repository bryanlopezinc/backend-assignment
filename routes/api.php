<?php

use App\Http\Controllers\FetchVesselTracksController;
use Illuminate\Support\Facades\Route;

Route::get('vesels/tracks', FetchVesselTracksController::class)->name('fetchVesselsTracks');
