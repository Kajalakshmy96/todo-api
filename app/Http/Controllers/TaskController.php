<?php

namespace App\Http\Controllers;

use App\Task;
use ApplicationDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function getTasks(Request $request)
    {
        $tasksList = Task::getTasksList(Auth::user()->id, $request->page, $request->limit);
        if (!empty($tasksList)) {
            $tasksListObj = array();
            foreach ($tasksList as $task) {
                array_push($tasksListObj, array(
                    "id" => $task['id'],
                    "category_id" => $task['category_id'],
                    "title" => $task['title'],
                    "date" => $task['date'],
                    "time" => $task['time'],
                    "remind" => $task['remind'],
                    "status" => $task['status'],
                    "created_at" => $task['created_at'],
                ));
            }
            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, $tasksListObj);
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "No more tasks to load!");
        }

        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }

    public function getTask(Request $request)
    {
        $task = Task::find($request->id);

        if (!empty($task)) {
            $taskObj = array(
                "id" => $task['id'],
                "category_id" => $task['category_id'],
                "title" => $task['title'],
                "description" => $task['description'],
                "date" => $task['date'],
                "time" => $task['time'],
                "remind" => $task['remind'],
                "status" => $task['status'],
                "created_at" => $task['created_at']
            );
            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, $taskObj);
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "Unable to get the task details!");
        }

        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }

    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'bail|required|numeric',
            'title' => 'bail|required|string|max:255',
            //'description' => 'required|string',
            'date' => 'required|date|date_format:Y-m-d|after:yesterday',
            'time' => 'required|date_format:H:i:s'
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), $validator->messages()->first());
        } else {
            $task = new Task();
            $task->category_id = $request->category_id;
            $task->user_id = Auth::user()->id;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->date = $request->date;
            $task->time = $request->time;
            $task->remind = $request->remind;
            $task->status = Task::TASK_STATUS_ACTIVE;
            $task->created_at = ApplicationDateTime::now();
            $task->save();

            if ($task->id != null) {
                return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, array(
                    "created" => true,
                    "id" => $task->id,
                    "task" => $task
                ));
            } else {
                return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array());
            }
        }
    }

    public function updateTask(Request $request)
    {
        $task = Task::find($request->id);

        if (!empty($task)) {
            $task->category_id = $request->category_id;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->date = $request->date;
            $task->time = $request->time;
            $task->remind = $request->remind;
            $task->updated_at = ApplicationDateTime::now();
            $task->created_at = ApplicationDateTime::now();
            $task->save();

            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, array(
                "updated" => true,
                "id" => $task->id,
                "task" => $task
            ));
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "Unable to find the task to update!");
        }

        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }

    public function updateState(Request $request)
    {
        $task = Task::find($request->id);

        if (!empty($task)) {
            $task->state = $request->state;
            $task->state_updated_at = ApplicationDateTime::now();
            $task->save();

            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, array(
                "updated" => true,
                "id" => $task->id,
                "task" => $task
            ));
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "Unable to find the task to update!");
        }

        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }

    public function deleteTask(Request $request)
    {
        $task = Task::find($request->id);

        if (!empty($task)) {
            $task->status = Task::TASK_STATUS_DELETED;
            $task->updated_at = ApplicationDateTime::now();
            $task->save();
            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, array(
                "deleted" => true,
                "id" => $task->id
            ));
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "Unable to find the task to delete!");
        }

        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }
}
