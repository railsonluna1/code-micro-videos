<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use Uuid, SoftDeletes;

    const RATING_LIST = ['L', '10', '12', '14', '18'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_launched' => 'integer',
        'duration' => 'integer'
    ];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'duration',
        'opened',
        'rating',
    ];
    public $incrementing = false;

}
