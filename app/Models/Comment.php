<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'comment',
        'approved',
        'post_id',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getDateAttribute()
    {
        return date("d F, Y (l)", strtotime($this->created_at));
    }

    public function getAuthorAttribute()
    {
        if($this->user_id) {
            $author = User::find($this->user_id);

            return $author->name.' ( '.$author->email.' ) ';
        }
        else {
            return $this->name.' ( '.$this->email.' ) ';
        }
    }
}