<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = ['name', 'time', 'status', 'priority',
                            'end_date', 'user_id', 'desc', 'finished',
                            'timer', 'comment'];

}
