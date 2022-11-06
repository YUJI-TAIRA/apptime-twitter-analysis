<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsTwitterListUser extends Model
{
    use HasFactory;

    protected $table = 'ms_twitter_list_user';
    protected $primaryKey = ['user_id', 'list_id'];
    
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'list_id',
    ];
}
