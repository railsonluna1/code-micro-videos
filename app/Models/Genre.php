<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;
    use Uuid;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $fillable = ['name', 'is_active'];

    public $incrementing = false;
    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];
}
