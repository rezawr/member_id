<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'exchanges_point',
        'image',
    ];

    protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        return asset("/storage/awards/{$this->attributes['image']}");
    }
}
