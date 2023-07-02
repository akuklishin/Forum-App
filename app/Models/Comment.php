<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'postId',
        'creationTime',
        'content',
        'scoreCount'
    ];

    public $timestamps = false;
}
