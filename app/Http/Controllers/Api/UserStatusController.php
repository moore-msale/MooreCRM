<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\User;

class UserStatusController extends BaseController
{
	public function statusOffline($id){
		$user = User::find($id);
		
		if (is_null($user)) {
            return $this->sendError("User not found");
        }

        $user->status = 'offline';
        $user->save();

        SendNotification::sendPusher("user");
        return $this->sendResponse($user->toArray(),"User is offline", "user");
	}    
}
