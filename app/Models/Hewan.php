<?php

namespace App\Models;

use App\Models\Type;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hewan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->translatedFormat('l, d F Y H:i:s');
    }

}