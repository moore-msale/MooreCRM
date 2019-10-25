<?php


namespace App\Http\Controllers\Api;

use App\Todo;
use Illuminate\Http\Request;
use Validator;

class TodoApiController extends BaseController
{
    public function index()
    {
        $user_id = SendNotification::getUserId();
        $todos = Todo::where('user_id', $user_id)->get();
        return $this->sendResponse($todos->toArray(), "Todo retrieved successfully.", "todo");
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $this->sendError("Validation error", $validator->errors());
        }
        $todo = Todo::create($input);
        SendNotification::sendPusher("todo");
        return $this->sendResponse($todo->toArray(), "Todo created successfully", "todo");
    }

    public function show(Request $request, $id)
    {
        $todo = Todo::find($request->input('todo_id'));
        if (is_null($todo)) {
            return $this->sendError("Todo not found");
        }
        return $this->sendResponse($todo->toArray(), "Todo view", "todo");
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $todo = Todo::find($id);
        if (is_null($todo)) {
            return $this->sendError("Todo not found");
        }
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors());
        }
        $todo->name = $request->input('name');
        $todo->desc = $request->input('desc');
        $todo->date = $request->input('date');
        $todo->save();
        SendNotification::sendPusher("todo");
        return $this->sendResponse($todo->toArray(), "Todo successfully updated", "todo");
    }

    public function delete(Request $request)
    {
        $todo = Todo::find($request->input('todo_id'));
        if (is_null($todo)) {
            return $this->sendError("Todo not found");
        }
        $todo->delete();
        return $this->sendResponse($todo->toArray(), "Todo deleted", "todo");
    }
}
