<?php


namespace App\Http\Controllers\Api;

use App\Events\AddedTask;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Task;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Pusher\PusherException;
use Validator;
use JWTAuth;


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

        $client = new Client();

        $validator = Validator::make($input, [
            'name' => 'required',
            'time' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'day' => 'required',
            'end_date' => 'required',
            'user_id' => 'required',
            'desc' => 'required',
            'timer' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task = Task::create($input);
        $name = $input['name'];
        $url = "https://api.telegram.org/bot925882756:AAEt3HsNT_PWsK_bYFzhFqXZUaq34Ayiz0c/sendMessage?chat_id=160868894&text=\"$name\"";
        $response = $client->request('POST', $url);
        $code = $response->getStatusCode();
        $this->sendPusher();
        return $this->sendResponse($task->toArray(), 'Task created successfully.');
    }

    public function sendPusher()
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        );
        $pusher = new Pusher(env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options);
        $data['message'] = 'task created';
        try {
            $pusher->trigger('moore-channel', 'moore-event', $data);
        } catch (PusherException $e) {
            Log::debug("error push");
        }
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
            'timer' => 'required',
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
        $task->timer = $input['timer'];
        $task->save();

        return $this->sendResponse($task->toArray(), 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return $this->sendResponse($task->toArray(), 'Task deleted successfully.');
    }

    public function updateTimer(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return $this->sendError('Task Error.', 'Task not found');
        }
        $task->timer = $request->input('timer');
        $task->save();

        return $this->sendResponse($task->toArray(), 'Task updated successfully.');
    }

    public function setFinished(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return $this->sendError('Task Error.', 'Task not found');
        }
        $task->status = 0;
        $task->timer = $request->input('timer');
        $task->finished = 1;
        $task->save();

        return $this->sendResponse($task->toArray(), 'Task finished updated successfully.');
    }
}
