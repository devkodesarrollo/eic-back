<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Util\Constants;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::USER_NAME => Constants::USER_RULES_NAME,
            Constants::USER_EMAIL => Constants::USER_RULES_EMAIL,
            Constants::USER_PASSWORD => Constants::USER_RULES_PASSWORD,
        ]);

        if ($validator->fails()) {
            return response()->json([Constants::ERROR => $validator->errors()], 400);
        }

        // Crear un nuevo usuario
        $user = User::create([
            Constants::USER_NAME => $request->name,
            Constants::USER_EMAIL => $request->email,
            Constants::USER_PASSWORD => Hash::make($request->password),
        ]);

        // Generar el token JWT
        $token = JWTAuth::fromUser($user);

        return response()->json([
            Constants::TOKEN => $token, 
            Constants::MESSAGE => Constants::USER_REGISTER_SUCCESS
        ], 201);
    }

    // Login de usuario
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::USER_EMAIL => Constants::USER_RULES_EMAIL_2,
            Constants::USER_PASSWORD => Constants::USER_RULES_PASSWORD,
        ]);

        if ($validator->fails()) {
            return response()->json([Constants::ERROR => $validator->errors()], 400);
        }

        // Intentar autenticar al usuario y generar el token
        if (!$token = JWTAuth::attempt($request->only(Constants::USER_EMAIL, Constants::USER_PASSWORD))) {
            return response()->json([Constants::ERROR => Constants::USER_LOGIN_INVALID], 401);
        }

        $post = (object) $request->all();

        $user = User::where(Constants::USER_EMAIL, $post->email)->first();

        return response()->json([
            Constants::USER => (object) [
                Constants::USER_FULL_NAME => $user->name,
                Constants::USER_EMAIL => $user->email
            ],
            Constants::TOKEN => $token
        ]);
    }
}


