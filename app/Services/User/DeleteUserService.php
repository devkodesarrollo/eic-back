<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Util\Constants;

class DeleteUserService{

    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function delete($id){
        $model = $this->repository->find($id);
        if(!$model){
            $model = $this->repository->new();
            $model->errors = (array) Constants::USER_NOT_FOUND;
            return $model;
        }

        $delete = $this->repository->delete($model);
        return $delete;
    }

}