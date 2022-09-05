<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Article extends Model
{
    use HasFactory;
    protected $fillable=[
        'art_link',
        'published_at',
        'title',
        'disc',
        'website_id',
        'opr_id'
    ];
    protected static $logAttribute=[
        'art_link',
        'published_at',
        'title',
        'disc',
        'website_id',
        'opr_id'
    ];
}
