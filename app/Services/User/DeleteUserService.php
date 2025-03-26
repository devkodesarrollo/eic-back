<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Util\Constants;
use App\Exceptions\ValidationException;

class DeleteUserService{

    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function delete($id){
        $model = $this->repository->find($id);
        if(!$model) throw new ValidationException((array) Constants::USER_NOT_FOUND);
        $delete = $this->repository->delete($model);
        return $delete;
    }

}