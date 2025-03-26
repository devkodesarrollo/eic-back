<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; 

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'role_id'
    ];

    public $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
        'role_id' => 'required'
    ];

    public $messages = [
        'role_id.required' => 'El campo rol es obligatorio.'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['role'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRules($isPatch = false, $requestData = [])
    {
        $rules = $this->rules;

        if ($isPatch) {
            foreach ($rules as $key => $rule) {
                if (!array_key_exists($key, $requestData)) {
                    $rules[$key] = str_replace('required|', '', $rule);
                    $rules[$key] = str_replace('required', '', $rules[$key]);
                }
            }
        }

        return $rules;
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }
}
