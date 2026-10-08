<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'ringkasan',
        'gambar',
        'caption_image',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
