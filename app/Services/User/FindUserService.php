<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Util\Constants;
use App\Exceptions\ValidationException;

class FindUserService{

    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function find($id){
        $model = $this->repository->find($id);
        if(!$model) throw new ValidationException((array) Constants::USER_NOT_EXIST_ID);
        return $model;
    }

}