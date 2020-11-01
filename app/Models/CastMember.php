<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use SoftDeletes;
    use Uuid;

    public const TYPE_DIRECTOR = 1;
    public const TYPE_ACTOR = 2;

    protected $fillable = ['name', 'type'];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public $incrementing = false;
    protected $casts = [
        'id' => 'string'
    ];
}
