<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Util\Constants;
use App\Services\User\FindUserService;
use App\Services\User\GetAllUserService;
use App\Services\User\CreateUserService;
use App\Services\User\UpdateUserService;
use App\Services\User\ChangeFieldUserService;
use App\Services\User\DeleteUserService;

class UserController extends Controller{

    
    private $findUserService;
    private $getAllUserService;
    private $createUserService;
    private $updateUserService;
    private $changeFieldUserService;
    private $deleteUserService;

    public function __construct(
        FindUserService $findUserService,
        GetAllUserService $getAllUserService,
        CreateUserService $createUserService,
        UpdateUserService $updateUserService,
        ChangeFieldUserService $changeFieldUserService,
        DeleteUserService $deleteUserService
    ){
        $this->findUserService = $findUserService;
        $this->getAllUserService = $getAllUserService;
        $this->createUserService = $createUserService;
        $this->updateUserService = $updateUserService;
        $this->changeFieldUserService = $changeFieldUserService;
        $this->deleteUserService = $deleteUserService;
    }

    public function find($id){
        $model = $this->findUserService->find($id);
        return !isset($model->errors) ? $this->resolve([Constants::DATA => $model, Constants::ERRORS => []]) : $this->resolve([Constants::DATA => null, Constants::ERRORS => $model->errors], Constants::STATUS_BAD_REQUEST);
    }

    public function all(){
        $list = $this->getAllUserService->all();
        return $this->resolve([Constants::DATA => $list]);
    }

    public function save(Request $request){
        $model = $this->createUserService->save($request);
        return !isset($model->errors) ? $this->resolve([Constants::MESSAGE => Constants::USER_CREATE_SUCCESS, Constants::ERRORS => []]) : $this->resolve([Constants::MESSAGE => Constants::USER_CREATE_ERROR, Constants::ERRORS => $model->errors], Constants::STATUS_BAD_REQUEST);
    }

    public function update(Request $request, $id){
        $model = $this->updateUserService->update($request, $id);
        return !isset($model->errors) ? $this->resolve([Constants::MESSAGE => Constants::USER_UPDATE_SUCCESS, Constants::ERRORS => []]) : $this->resolve([Constants::MESSAGE => Constants::USER_UPDATE_ERROR, Constants::ERRORS => $model->errors], Constants::STATUS_BAD_REQUEST);
    }

    public function changeField(Request $request, $id){
        $model = $this->changeFieldUserService->changeField($request, $id);
        return !isset($model->errors) ? $this->resolve([Constants::MESSAGE => Constants::USER_UPDATE_SUCCESS, Constants::ERRORS => []]) : $this->resolve([Constants::MESSAGE => Constants::USER_UPDATE_ERROR, Constants::ERRORS => $model->errors], Constants::STATUS_BAD_REQUEST);
    }

    public function delete($id){
        $model = $this->deleteUserService->delete($id);
        return !isset($model->errors) ? $this->resolve([Constants::MESSAGE => Constants::USER_DELETE_SUCCESS, Constants::ERRORS => []]) : $this->resolve([Constants::MESSAGE => Constants::USER_DELETE_ERROR, Constants::ERRORS => $model->errors], Constants::STATUS_BAD_REQUEST);
    }
}
