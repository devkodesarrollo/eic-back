<?php

namespace App\Util;

class Parser
{
    public static function getNameMonth($numberFromDate) {
        if ($numberFromDate == "01") return "Enero";
        if ($numberFromDate == "02") return "Febrero";
        if ($numberFromDate == "03") return "Marzo";
        if ($numberFromDate == "04") return "Abril";
        if ($numberFromDate == "05") return "Mayo";
        if ($numberFromDate == "06") return "Junio";
        if ($numberFromDate == "07") return "Julio";
        if ($numberFromDate == "08") return "Agosto";
        if ($numberFromDate == "09") return "Septiembre";
        if ($numberFromDate == "10") return "Octubre";
        if ($numberFromDate == "11") return "Noviembre";
        if ($numberFromDate == "12") return "Diciembre";
        return "Error"; 
    }
}