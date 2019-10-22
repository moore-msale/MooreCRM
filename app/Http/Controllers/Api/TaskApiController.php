<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Task;
use Illuminate\Http\Request;
use Validator;

class TaskApiController extends BaseController
{
    public function index()
    {
        $tasks = Task::all();
        return $this->sendResponse($tasks->toArray(), 'Tasks retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'time' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'day' => 'required',
            'end_date' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task = Task::create($input);


        return $this->sendResponse($task->toArray(), 'Task created successfully.');
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        return $this->sendResponse($task->toArray(), 'Task retrieved successfully.');
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
            'day' => 'required',
            'end_date' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->name = $input['name'];
        $task->time = $input['time'];
        $task->status = $input['status'];
        $task->priority = $input['priority'];
        $task->day = $input['day'];
        $task->end_date = $input['end_date'];
        $task->user_id = $input['user_id'];
        $task->desc = $input['desc'];
        $task->save();

        return $this->sendResponse($task->toArray(), 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return $this->sendResponse($task->toArray(), 'Task deleted successfully.');
    }
}
