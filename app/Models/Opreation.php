<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
class Opreation extends Model
{
    use HasFactory;
    protected static $logAttributes = ['website_id','user_id'];
    protected $fillable = [
        'website_id',
        'user_id'
    ];
}
