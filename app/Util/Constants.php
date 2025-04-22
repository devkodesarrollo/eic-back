<?php

namespace App\Util;

class Constants
{
    const MESSAGE = 'message';
    const ERROR = 'error';
    const ERRORS = 'errors';
    const USER = 'user';
    const TOKEN = 'token';
    const EXPIRED = 'expires_in';
    const NEW_TOKEN = 'new_token';
    const DATA = 'data';

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
    const MESSAGE_ERROR_SERVER = "Ocurrió un error en la petición realizada";

    //ATTRIBUTES MODEL USER
    const USER_ID = "id";
    const USER_NAME = "name";
    const USER_EMAIL = "email";
    const USER_PASSWORD = "password";
    const USER_STATE = "state";
    const USER_ROLE_ID = "role_id";

    const USER_CREATE_SUCCESS = "Usuario creado correctamente.";
    const USER_CREATE_ERROR = "Ocurrió un error al crear el usuario, intente nuevamente.";
    const USER_CREATE_ROLE_NOT_FOUND = "El rol que intenta asignar al usuario no existe.";
    const USER_CREATE_EMAIL_EXIST = "Ya existe un usuario registrado con el email ingresado.";
    const USER_NOT_FOUND = "El usuario no existe.";
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
}