<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    const TASK_STATUS_ACTIVE = "A";
    const TASK_STATUS_INACTIVE = "I";
    const TASK_STATUS_DELETED = "D";

    const TASK_REMINDER_ON = 1;
    const TASK_REMINDER_OFF = 0;


    //table name
    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'category_id', 'title', 'description', 'date', 'time', 'remind', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [];

    static function getTasksList($userId, $page = 1, $limit = 10)
    {
        // $sql = "select * from task t
        // where user_id = '" . $userId . "'
        // order by id desc
        // limit " . (($page - 1) * 10) . ", " . $limit . "";
        // $tasksList = DB::select($sql);

        $tasksList = Task::query()
            ->where('user_id', $userId)
            ->where('status', Task::TASK_STATUS_ACTIVE)
            ->orderBy('id', 'desc')
            ->take($limit)->skip((($page - 1) * 10))
            ->get();

        //var_dump($tasksList);exit();
        return $tasksList;
    }
}
