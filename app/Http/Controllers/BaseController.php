<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Response;

class BaseController extends Controller
{

	protected function errorResponse($title,$message){
		return Response::json(
				[$title => null,'error' => ['message' => $message.' does not exist']] 
				,404);
	}

	protected function successResponse($title,$response){
		return Response::json([$title => $response,'error' => null] ,200);
	}
}