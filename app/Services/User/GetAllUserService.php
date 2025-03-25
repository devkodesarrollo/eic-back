<?php

namespace App\Services\User;

use App\Repositories\UserRepository;

class GetAllUserService{

    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function all(){
        return $this->repository->all();
    }

}