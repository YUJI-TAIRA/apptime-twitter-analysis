<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsEmployee extends Model
{
    use HasFactory;

    protected $table = 'ms_employees';
    protected $primaryKey = 'employee_id';
    
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'is_incentive',
        'is_deleted',
    ];
    protected $casts = [
        'is_incentive' => 'boolean',
        'is_deleted' => 'boolean',
    ];
    public $timestamps = true;

    public function msTweetUser()
    {
        return $this->belongsTo(MsTweetUser::class, 'user_id', 'user_id');
    }
}
