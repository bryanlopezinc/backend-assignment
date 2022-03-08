<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class VesselTrack extends Model
{
    /** {@inheritdoc} */
    public $timestamps = false;

    /** {@inheritdoc} */
    protected $guarded = [];
    
    /** {@inheritdoc} */
    protected $table = 'vessels_tracks';
}
