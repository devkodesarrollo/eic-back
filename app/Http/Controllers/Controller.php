<?php

namespace App\Http\Controllers;

use App\Util\Constants;

abstract class Controller
{
    public function resolve(
        $data = null, 
        $message = Constants::MESSAGE_OK, 
        $error = false, 
        $status = Constants::STATUS_OK
    ){
        return response()->json([
            Constants::ERROR => $error,
            Constants::MESSAGE => $message,
            Constants::DATA => $data
        ], $status);
    }
}
