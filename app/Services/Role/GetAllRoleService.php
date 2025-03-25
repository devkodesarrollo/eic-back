<?php

namespace App\Services\Role;

use App\Repositories\RoleRepository;

class GetAllRoleService{

    private $repository;

    public function __construct(RoleRepository $repository){
        $this->repository = $repository;
    }

    public function all(){
        return $this->repository->all();
    }
}