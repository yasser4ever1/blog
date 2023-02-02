<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'posts';
    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'image',
        'user_id',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('uploads/images/posts/uploads/' . $value);
        } 
        else {
            return asset('assets/images/no-image.png');
        }
    }

    public function getThumbnailAttribute($value)
    {
        if($this->attributes['image']) {
            return asset('uploads/images/posts/thumbnails/' . $this->attributes['image']);
        } 
        else {
            return asset('assets/images/no-image.png');
        }
    }

    public function getPublishDateAttribute()
    {
        return date("d F, Y (l)", strtotime($this->created_at));
    }

    public function getUpdateDateAttribute()
    {
        return date("d F, Y (l)", strtotime($this->updated_at));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
