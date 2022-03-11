<?php

use App\Http\Controllers\FetchVesselTracksController;
use Illuminate\Support\Facades\Route;

Route::get('vessels/tracks', FetchVesselTracksController::class)->name('fetchVesselsTracks');
