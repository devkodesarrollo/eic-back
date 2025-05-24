<?php

namespace App\Repositories;

use App\Util\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Repository
{
    public function save($model){
        try {
            DB::beginTransaction();

            if ($model->save()) {
                DB::commit();
                return $model;
            } else {
                DB::rollBack();
                return null;
            }
        } catch (QueryException $e) {
            $this->handleQueryException($e, $model);
        }
    }

    public function saveList($modelList){
        try { 
            DB::beginTransaction();
            $saveModelList = false;

            foreach ($modelList as $model){
                if($model->save()){
                    $saveModelList = true;
                }else{
                    $saveModelList = false;
                    break;
                }
            }

            if($saveModelList){
                DB::commit();
            }else{
                DB::rollBack();
            }
          
            return $saveModelList;
        } catch (QueryException $e) {
            // $this->handleQueryException($e, $model);
        }
    }

    public function update($model){
        try {
            DB::beginTransaction();

            if ($model->update()) {
                DB::commit();
                return $model;
            } else {
                DB::rollBack();
                return null;
            }
        } catch (QueryException $e) {
            $this->handleQueryException($e, $model);
        }
    }

    public function delete($model){
        try {
            DB::beginTransaction();

            if ($model->delete()) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (QueryException $e) {
            $this->handleQueryException($e, $model);
        }
    }

    // Función para manejar las excepciones de QueryException
    function handleQueryException(QueryException $e, $model){
        $errorCode = $e->errorInfo[1];
        $table_name = $model->getTable();
        $constantName = strtoupper($table_name);
        $errorMessage = $e->getMessage();
        $constantValue = $table_name;
        $array_list_model = Constants::ARRAY_LIST_MODULES;
        $model_name = $array_list_model[$table_name];
        
        if ($errorCode == Constants::ERROR_ID_DUPLICATE) {
            // Error de clave duplicada
            preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $errorMessage, $matches);
            if (count($matches) >= 3) {
                $nameOfColumnDuplicate = $matches[2];
                $tableReferenced = strtoupper($this->getReferencedTable($nameOfColumnDuplicate, $table_name));
                $ConstantTableReferenced = Constants::$constantName;
            }

            if (is_null($tableReferenced) || empty($tableReferenced)) {
                throw new \Exception("No puede registrar dos " . $ConstantTableReferenced . " con campos únicos iguales");
            } else {
                throw new \Exception("No puede registrar dos " . $model_name . " con " . $ConstantTableReferenced . " iguales");
            }
        } elseif ($errorCode == Constants::ERROR_LENGTH_EXCEEDED) {
            // Error de longitud excedida
            throw new \Exception("Utilice menos caracteres para guardar el registro");
        } elseif ($errorCode == Constants::ERROR_FOREIGN_KEY_VIOLATION) {
            // Error de violación de restricción de clave externa
            throw new \Exception("No puede eliminar " . $model_name . " si tiene registros asociados en otro módulo");
        }
        throw new \Exception("Ocurrió un error en la base de datos.");
    }

    // Función para obtener el nombre de la tabla referenciada por una clave foránea
    private function getReferencedTable($columnaForanea, $table_name)
    {
        // Realiza una consulta a la base de datos para obtener la información de la clave foránea
        $consulta = "SELECT referenced_table_name
                FROM information_schema.key_column_usage
                WHERE table_name = '$table_name' 
                AND column_name = '$columnaForanea'";
        $resultado = DB::select($consulta);

        if (!empty($resultado[1])) {
            return $resultado[1]->referenced_table_name;
        }
    }
}
