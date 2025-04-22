<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Util\Constants;
use App\Exceptions\ValidationException;

class ChangeFieldUserService{

    private $repository;

    public function __construct(
        UserRepository $repository
    ){
        $this->repository = $repository;
    }

    public function changeField($request, $id){
        $model = $this->repository->find($id);
        if(!$model) throw new ValidationException((array) Constants::USER_NOT_FOUND);

        $data = $request->only(array_keys($request->all()));
        $rules = $model->getRules($request->isMethod(Constants::PATCH), $request->all());
        $model->fill($data);
        $validator = \Validator::make($request->all(), $rules, $model->messages);
        $update = !$validator->fails() ? $this->repository->update($model) : null;

        if(!$update) throw new ValidationException($validator->messages()->all());
        
        return $model;
    }

}