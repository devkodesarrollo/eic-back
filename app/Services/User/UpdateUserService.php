<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Models\User;
use App\Util\Constants;

class UpdateUserService{

    private $repository;
    private $repositoryRole;

    public function __construct(
        UserRepository $repository,
        RoleRepository $repositoryRole
    ){
        $this->repository = $repository;
        $this->repositoryRole = $repositoryRole;
    }

    public function update($request, $id){
        $model = $this->repository->find($id);
        if(!$model){
            $model = $this->repository->new();
            $model->errors = (array) Constants::USER_NOT_FOUND;
            return $model;
        }

        $role = $this->repositoryRole->find($request->role_id);
        $existUserEmail = $this->repository->findByExist(Constants::USER_EMAIL, $request->email, $id);

        if(!$role){
            $model->errors = (array) Constants::USER_CREATE_ROLE_NOT_FOUND;
            return $model;
        }

        if($existUserEmail){
            $model->errors = (array) Constants::USER_CREATE_EMAIL_EXIST;
            return $model;
        }

        $model->fill($request->all());
        $model->password = bcrypt($request->password);
        $validator = \Validator::make($request->all(), $model->rules, $model->messages);
        $update = !$validator->fails() ? $this->repository->update($model) : null;
        if(!$update){
            $model->errors = $validator->messages()->all();
        }

        return $model;
    }

}