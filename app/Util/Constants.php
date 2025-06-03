<?php

namespace App\Util;

class Constants
{
    const MESSAGE = 'message';
    const ERROR = 'error';
    const ERRORS = 'errors';
    const USER = 'user';
    const TOKEN = 'token';
    const EXPIRED_IN = 'expires_in';
    const EXPIRED_AT = 'expires_at';
    const NEW_TOKEN = 'new_token';
    const DATA = 'data';
    const MESSAGE_OK = 'OK';

    //API METHODS
    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const PATCH = 'patch';
    const DELETE = 'delete';

    //STATUS CODE
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_ERROR_SERVER = 500;
    const MESSAGE_ERROR_SERVER = "Ocurrió un error en la petición realizada.";
    const MESSAGE_ERROR_TOKEN_EXPIRED = "Token expirado.";
    const MESSAGE_ERROR_TOKEN_INVALID = "Token inválido.";
    const MESSAGE_ERROR_NOT_PROCESS_TOKEN = "No se pudo procesar el token.";
    const MESSAGE_ERROR_TOKEN_EMPTY = "Token no proporcionado.";

    // CODIGOS DE ERROR DE BASE DE DATOS
   const ERROR_ID_DUPLICATE = 1062;
   const ERROR_FOREIGN_KEY_VIOLATION = 1451;
   const ERROR_LENGTH_EXCEEDED = 1406;

   const ARRAY_LIST_MODULES = [
        "licitacion" => "Licitacion",
        "metric" => "Métrica",
        "role" => "Rol",
        "trm" => "TRM",
        "User" => "Usuario",
    ];

    //ATTRIBUTES MODEL USER
    const USER_ID = "id";
    const USER_FULL_NAME = "full_name";
    const USER_NAME = "name";
    const USER_EMAIL = "email";
    const USER_PASSWORD = "password";
    const USER_STATE = "state";
    const USER_ROLE_ID = "role_id";

    const USER_LOGIN_INVALID = "Credenciales inválidas o usuario inactivo. Por favor, verifica tus datos o contacta al administrador del sistema.";
    const USER_CREATE_SUCCESS = "Usuario creado correctamente.";
    const USER_REGISTER_SUCCESS = "Usuario registrado correctamente.";
    const USER_CREATE_ERROR = "Ocurrió un error al crear el usuario, intente nuevamente.";
    const USER_CREATE_ROLE_NOT_FOUND = "El rol que intenta asignar al usuario no existe.";
    const USER_CREATE_EMAIL_EXIST = "Ya existe un usuario registrado con el email ingresado.";
    const USER_NOT_FOUND = "El usuario no existe.";
    const USER_NOT_EXIST_ID = "El id del usuario no existe.";
    const USER_UPDATE_SUCCESS = "Usuario actualizado correctamente.";
    const USER_UPDATE_ERROR = "Ocurrió un error al actualizar el usuario, intente nuevamente.";
    const USER_DELETE_SUCCESS = "Usuario eliminado correctamente.";
    const USER_DELETE_ERROR = "Ocurrió un error al eliminar el usuario, intente nuevamente.";

    const LICITACION_CREATE_SUCCESS = "Licitación creada correctamente.";
    const LICITACION_CREATE_ERROR = "Ocurrió un error al crear la licitación, intente nuevamente.";
    const LICITACION_CREATE_PROCESO_EXIST = "Ya existe una licitación registrado con el id_proceso ingresado.";
    const LICITACION_NOT_FOUND = "La licitación no existe.";
    const LICITACION_UPDATE_SUCCESS = "Licitación actualizada correctamente.";
    const LICITACION_UPDATE_ERROR = "Ocurrió un error al actualizar la licitación, intente nuevamente.";
    const LICITACION_DELETE_SUCCESS = "Licitación eliminada correctamente.";
    const LICITACION_DELETE_ERROR = "Ocurrió un error al eliminar la licitación, intente nuevamente.";
    const USER_RULES_NAME = "required|string|max:255";
    const USER_RULES_EMAIL = "required|string|email|max:255|unique:users";
    const USER_RULES_EMAIL_2 = "required|string|email";
    const USER_RULES_PASSWORD = "required|string";

    const ERROR_REPORT_GENERATE = "Error al generar el reporte: ";
    const ERROR_GENERATE_RESULT = "Error al calcular los resultados: ";
    const METRICS_SAVE_SUCCESSFULL = "Registro almacenado exitosamente";

    const RESULT_NOT_FOUND_API = "No se encontraron datos en la respuesta del API";
    const ERROR_GET_DATA_API = "Error al consumir el API: ";
    const TENDERS_SUCCESSFULLY_SYNCHRONIZED = " licitaciones nuevas sincronizadas exitosamente mayores a ";
    const ERROR_PRICE_PARTICIPANT_GREATER_ZERO = "El precio del participante debe ser mayor a cero.";
    const PARTICIPANTS_VALID_NOT_FOUND = "No se encontraron participantes validos para el estudio de probabilidades.";
    const START_YEAR_REQUIRED = "El año inicial es requerido.";
    const END_YEAR_REQUIRED = "El año final es requerido.";
    const VALUE_CONTRACT_REQUIRED = "El valor del contrato es requerido.";
    const TOTAL_PARTICIPANTS_REQUIRED = "La cantidad de participantes es requerido.";
    const CONTRACT_TYPE_REQUIRED = "La modalidad del contrato es requerido.";
    const START_PERCENTAGE_REQUIRED = "El porcentaje de participacion inicial es requerido.";
    const END_PERCENTAGE_REQUIRED = "El porcentaje de participacion final es requerido.";
    const DATE_PARTICIPATION_REQUIRED = "La fecha de participación es requerida para el calculo de probabilidad de la TRM.";

    const NAME_METRICS_REQUIRED = "El nombre es requerido.";
    const RESULT_METRIC_REQUIRED = "El resultado es requerido.";

    const START_DATE_REQUIRED = "La fecha inicial es requerida.";
    const END_DATE_REQUIRED = "La fecha final es requerida.";
    const START_DATE_NOT_GREATER_END_DATE = "La fecha de inicio no puedce ser mayor a la final.";

}