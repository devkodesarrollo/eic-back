<?php

namespace App\Util;

class Validators
{
    public static function isValid($data) {
        return $data != "" && $data != null; 
    }

    public static function truncateFloat($number, $digitos)
    {
        $multiplicador = 100000;
        $resultado = ((int)($number * $multiplicador)) / $multiplicador;
        return number_format($resultado, $digitos);
    }
}