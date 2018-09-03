<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @property integer $id
 * @property string $sname
 * @property string $name
 * @package App\Models
 */
class Account extends Model
{
    protected $table = 'accounts';
    public $timestamps = false;
}
