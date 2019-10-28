<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;


class TaskApiController extends BaseController
{
    public function index()
    {
        $tasks = Task::all();
        return $this->sendResponse($tasks->toArray(), 'Tasks retrieved successfully.', "tasks");
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'time' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'end_date' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task = Task::create($input);
        $name = $input['name'];
        $desc = $input['desc'];
        SendNotification::sendPusher("task");
        $user = User::find($input['user_id']);
        $txt = "Задача: ".$name."<br>Описание: ".$desc;
        SendNotification::sendBot($user->telegram_id, $txt);
        return $this->sendResponse($task->toArray(), 'Task created successfully.', "tasks");
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        return $this->sendResponse($task->toArray(), 'Task retrieved successfully.', "tasks");
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $task = Task::find($id);

        $validator = Validator::make($input, [
            'name' => 'required',
            'time' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'end_date' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
            'timer' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->name = $input['name'];
        $task->time = $input['time'];
        $task->status = $input['status'];
        $task->priority = $input['priority'];
        $task->end_date = $input['end_date'];
        $task->user_id = $input['user_id'];
        $task->desc = $input['desc'];
        $task->save();
        SendNotification::sendPusher("task");
        return $this->sendResponse($task->toArray(), 'Task updated successfully.', "tasks");
    }

    public function destroy(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return $this->sendError('Task Error.', 'Task not found');
        }
        $task->delete();
        SendNotification::sendPusher("task");
        return $this->sendResponse($task->toArray(), 'Task deleted successfully.', "tasks");
    }

    public function updateTimer(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return $this->sendError('Task Error.', 'Task not found');
        }
        $task->status = 1;
        $task->timer = $request->input('timer');
        $task->save();
        SendNotification::sendPusher("task");
        return $this->sendResponse($task->toArray(), 'Task updated successfully.', "tasks");
    }

    public function setFinished(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return $this->sendError('Task Error.', 'Task not found');
        }
        $task->status = 0;
        $task->comment = $request->input('comment');
        $task->timer = $request->input('timer');
        $task->finished = 1;
        $task->save();
        SendNotification::sendPusher("task");
        return $this->sendResponse($task->toArray(), 'Task finished updated successfully.', "tasks");
    }

    public function getReports(Request $request)
    {
        $tasks = Task::where('finished', 1)->get();
        return $this->sendResponse($tasks->toArray(),'Reports', 'task');
    }
}
