<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
class ScrabedWebsite extends Model
{
    use HasFactory;
    protected static $logAttributes = ['name','userid'];
    protected $fillable = [
        'link',
        'name',
        'createddate',
        'userid'
    ];
}
