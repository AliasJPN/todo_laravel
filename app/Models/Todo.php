<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /**
     * 一括割り当て（マスアサインメント）可能な属性
     *
     * @var array<int, string>
    **/
    protected $fillable = [
        'title',
        'is_completed',
    ];
}
