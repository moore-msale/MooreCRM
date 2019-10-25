<?php


namespace App\Http\Controllers\Api;


use App\Todo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Validator;

class TodoApiController extends BaseController
{
    public function index()
    {
        $todos = Todo::all();
        return $this->sendResponse($todos->toArray(), "Todo retrieved successfully.", "todo");
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $client = new Client();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if ($validator->fails()){
            $this->sendError("Validation error", $validator->errors());
        }
        $todo = Todo::create($input);

        return $this->sendResponse($todo->toArray(),"Todo created successfully","todo");
    }
}
