<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Util\Constants;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException; // Excepciones de JWT
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
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
            return $this->resolve(null, $validator->errors(), true, Constants::STATUS_BAD_REQUEST);
            // return response()->json([Constants::ERROR => $validator->errors()], Constants::STATUS_BAD_REQUEST);
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
            return $this->resolve(null, $validator->errors(), true, Constants::STATUS_BAD_REQUEST);
            // return response()->json([Constants::ERROR => $validator->errors()], Constants::STATUS_BAD_REQUEST);
        }

        // Intentar autenticar al usuario y generar el token
        if (!$token = JWTAuth::attempt($request->only(Constants::USER_EMAIL, Constants::USER_PASSWORD))) {
            return $this->resolve(null, Constants::USER_LOGIN_INVALID, true, Constants::STATUS_UNAUTHORIZED);
        }

        $post = (object) $request->all();

        $user = User::where(Constants::USER_EMAIL, $post->email)->first();
        if($user->state == 0){
            return $this->resolve(null, Constants::USER_LOGIN_INVALID, true, Constants::STATUS_UNAUTHORIZED);
        }

        // Obtener el tiempo de expiración en minutos
        $expiration = JWTAuth::factory()->getTTL();

        // Obtener la fecha exacta de expiración en timestamp UNIX
        $expirationTimestamp = now()->addMinutes($expiration)->timestamp;

        return response()->json([
            Constants::USER => $user,
            Constants::TOKEN => $token,
            Constants::EXPIRED_IN => $expiration * 60,
            Constants::EXPIRED_AT => $expirationTimestamp
        ]);
    }

    public function refresh(){
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([Constants::ERROR => Constants::MESSAGE_ERROR_TOKEN_EMPTY], Constants::STATUS_BAD_REQUEST);
            }

            $newToken = JWTAuth::refresh($token);

            // Obtener el usuario asociado al nuevo token
            $user = JWTAuth::setToken($newToken)->toUser();

            // Obtener el tiempo de expiración en minutos
            $expiration = JWTAuth::factory()->getTTL();

            // Obtener la fecha exacta de expiración en timestamp UNIX
            $expirationTimestamp = now()->addMinutes($expiration)->timestamp;
            
            return response()->json([
                Constants::USER => $user,
                Constants::TOKEN => $newToken,
                Constants::EXPIRED_IN => $expiration * 60,
                Constants::EXPIRED_AT => $expirationTimestamp
            ]);

        } catch (TokenExpiredException $e) {
            return response()->json([Constants::ERROR => Constants::MESSAGE_ERROR_TOKEN_EXPIRED], Constants::STATUS_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json([Constants::ERROR => Constants::MESSAGE_ERROR_TOKEN_INVALID], Constants::STATUS_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json([Constants::ERROR => Constants::MESSAGE_ERROR_NOT_PROCESS_TOKEN], Constants::STATUS_UNAUTHORIZED);
        }
    }
}


