<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class OperatingTime
 * @property integer $id
 * @property Carbon $start
 * @property Carbon $finish
 * @property integer $computer_id
 * @property integer $account_id
 * @property Account $account
 * @property Computer $computer
 * @package App\Models
 */
class OperatingTime extends Model
{
    protected $table = 'operating_time';
    public $timestamps = false;
    protected $casts = [
        'start' => 'datetime',
        'finish' => 'datetime',
    ];

    // relations
    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function computer()
    {
        return $this->hasOne(Computer::class, 'id', 'computer_id');
    }
}
