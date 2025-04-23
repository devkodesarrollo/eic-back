<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Util\Constants;
use App\Services\Role\GetAllRoleService;

class RoleController extends Controller{
    private $getAllRoleService;

    public function __construct(
        GetAllRoleService $getAllRoleService
    ){
        $this->getAllRoleService = $getAllRoleService;
    }

    public function all(){
        $list = $this->getAllRoleService->all();
        return $this->resolve($list);
    }
}
