<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Models\User;
use App\Util\Constants;

class CreateUserService{

    private $repository;
    private $repositoryRole;

    public function __construct(
        UserRepository $repository,
        RoleRepository $repositoryRole
    ){
        $this->repository = $repository;
        $this->repositoryRole = $repositoryRole;
    }

    public function save($request){
        $role = $this->repositoryRole->find($request->role_id);
        $existUserEmail = $this->repository->findBy(Constants::USER_EMAIL, $request->email);
        $model = $this->repository->new();

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
        $save = !$validator->fails() ? $this->repository->save($model) : null;
        if(!$save){
            $model->errors = $validator->messages()->all();
        }

        return $model;
    }

}