<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hewan()
    {
        return $this->hasMany(Hewan::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->translatedFormat('l, d F Y H:i:s');
    }
}