<?php

namespace App\Http\Controllers;

use App\Util\Constants;

abstract class Controller
{
    public function resolve($data, $status = null){
        $status = $status == null ? Constants::STATUS_OK : $status;
        return response()->json($data, $status);
    }
}
