<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsEmployee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'is_incentive',
    ];
    public $timestamps = true;

    const IS_INCENTIVE_TRUE = 1;
    const IS_INCENTIVE_FALSE = 0;

    public function msTweetUser()
    {
        return $this->belongsTo(MsTweetUser::class, 'user_id', 'user_id');
    }
}
