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
use App\Exceptions\ValidationException;
use Exception;

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
        try{
            $model = $this->findUserService->find($id);
            return $this->resolve($model);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::USER_NOT_FOUND, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }        
    }

    public function all(){
        $list = $this->getAllUserService->all();
        return $this->resolve($list);
    }

    public function save(Request $request){
        try{
            $model = $this->createUserService->save($request);
            return $this->resolve($model, Constants::USER_CREATE_SUCCESS);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::USER_CREATE_ERROR, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }
    }

    public function update(Request $request, $id){
        try{
            $model = $this->updateUserService->update($request, $id);
            return $this->resolve($model, Constants::USER_UPDATE_SUCCESS);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::USER_UPDATE_ERROR, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }
    }

    public function changeField(Request $request, $id){
        try{
            $model = $this->changeFieldUserService->changeField($request, $id);
            return $this->resolve($model, Constants::USER_UPDATE_SUCCESS);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::USER_UPDATE_ERROR, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }        
    }

    public function delete($id){
        try{
            $this->deleteUserService->delete($id);
            return $this->resolve(null, Constants::USER_DELETE_SUCCESS);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::USER_DELETE_ERROR, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }
    }
}
