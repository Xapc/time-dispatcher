<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Computer
 * @property integer $id
 * @property string $name
 * @package App\Models
 */
class Computer extends Model
{
    protected $table = 'computers';
    public $timestamps = false;
}
