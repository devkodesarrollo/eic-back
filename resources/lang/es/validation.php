<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'max' => [
        'string' => 'El campo :attribute no debe superar los :max caracteres.',
    ],
    'unique' => 'El :attribute ya ha sido tomado.',
    'custom' => [
        'nombre_campo' => [
            'required' => 'Debes ingresar un valor en el campo nombre.',
        ],
    ],
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'role_id' => 'rol',
    ]
];

?>