<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const RESPONSE_SUCCESS_RETURN_CODE = 200;
    protected const RESPONSE_PROCESSING_ERROR_RETURN_CODE = 500;
    protected const RESPONSE_AUTH_ERROR_RETURN_CODE = 401;
    protected const RESPONSE_ACCESS_PRIVIDLEDGE_ERROR_RETURN_CODE = 402;
    protected const RESPONSE_BAD_REQUEST_RETURN_CODE = 400;

    protected function handleResponse($code, $data = array(), $error = '')
    {
        if ($code == Controller::RESPONSE_AUTH_ERROR_RETURN_CODE) {
            $error = "Your login session not available. Please try to login again!";
        }
        if ($code == Controller::RESPONSE_ACCESS_PRIVIDLEDGE_ERROR_RETURN_CODE) {
            $error = "Your dont have enough rights to access this details!";
        }
        if ($code == Controller::RESPONSE_BAD_REQUEST_RETURN_CODE) {
            $error = "Failed to handle. " . $error;
        }
        if ($code == Controller::RESPONSE_PROCESSING_ERROR_RETURN_CODE) {
            $error = "Failed to porcess. " . $error;
        }
        return response()->json(['code' =>  $code, 'result' => $data, 'error' => $error]);
    }
}
