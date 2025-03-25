<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Util\Constants;

class ChangeFieldUserService{

    private $repository;

    public function __construct(
        UserRepository $repository
    ){
        $this->repository = $repository;
    }

    public function changeField($request, $id){
        $model = $this->repository->find($id);
        if(!$model){
            $model = $this->repository->new();
            $model->errors = (array) Constants::USER_NOT_FOUND;
            return $model;
        }

        $data = $request->only(array_keys($request->all()));
        $rules = $model->getRules($request->isMethod(Constants::PATCH), $request->all());
        $model->fill($data);
        $validator = \Validator::make($request->all(), $rules, $model->messages);
        $update = !$validator->fails() ? $this->repository->update($model) : null;
        if(!$update){
            $model->errors = $validator->messages()->all();
        }

        return $model;
    }

}