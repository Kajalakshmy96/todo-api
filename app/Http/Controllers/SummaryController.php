<?php

namespace App\Http\Controllers;

use App\Task;
use ApplicationDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SummaryController extends Controller
{
    public function getSummary(Request $request)
    {
        $taskSummary = Task::getSummary(Auth::user()->id, $request->time_period);
        if (!empty($taskSummary)) {
            return $this->handleResponse(Controller::RESPONSE_SUCCESS_RETURN_CODE, $taskSummary);
        } else {
            return $this->handleResponse(Controller::RESPONSE_BAD_REQUEST_RETURN_CODE, array(), "Unable to load Customer Summary!");
        }
        //return $this->handleResponse(Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE, array(), $data['response']['error']['message']);
    }
}
