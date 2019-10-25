<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use JWTAuth;

class HomeApiController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function getUserTasks()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $id = $user['id'];
        $tasks = Task::where('user_id', $id)->where('finished', '!=', 1)->get();
        return response()->json(compact('tasks'));
    }

    public function getFinishedTasks()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $id = $user['id'];
        $tasks = Task::where('user_id', $id)->where('finished', 1)->get();
        return response()->json(compact('tasks'));
    }
}
