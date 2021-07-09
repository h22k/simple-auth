<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Kullanıcıyı ekstra parametreler ekleyerek kaydeder.
     *
     * @param object|array $data
     * @return bool
     */
    public function userRegister(object|array $data) : bool
    {

        $customData = [

            'ip_address'    => $_SERVER['REMOTE_ADDR'],
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'last_activity' => time(),
            'last_login'    => time()

        ];

        $mergedData = array_merge($data, $customData);

        return $this->save($mergedData);

    }

    /**
     * Laravel Mutator Özelliği
     *
     * @param $password
     */
    public function setPasswordAttribute($password) : void
    {

        $this->attributes['password'] = bcrypt($password);

    }
}
