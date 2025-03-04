<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['title','body','cover_image','pinned','deleted_at'];

    public function tags()
{
    return $this->belongsToMany(Tag::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
