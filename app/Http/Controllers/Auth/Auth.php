<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Routing\Redirector;

class Auth extends Controller
{

    /**
     * Genel request inputlarını sakladığımız değişken.
     *
     * @var array|null
     */
    private array|null $data;

    public function __construct()
    {

        $this->data = request()?->all();

    }

    /**
     * @param RegisterRequest $request
     * @return Redirector
     */
    public function register(RegisterRequest $request) : Redirector
    {

        $user = User::userRegister($this->data);

        if ($user) {
            $user = User::all()->last();
            \Auth::login($user, true);
            $this->setFlash('Başarıyla kayıt olundu!');
            return redirect(route('admin.home'));
        }

        $this->setFlash('Veritabanı bağlantı hatası!');
        return redirect(route('admin.register'));

    }

    /**
     * @param AuthRequest $request
     */
    public function login(AuthRequest $request)
    {

        $credentials = [
            //Email varlığını request sınıfında kontrol ettiğim için burada etmeme gerek yok.
            'email' => $this->data['email'],
            'password' => $this->data['password']
        ];

        if (\Auth::attempt($credentials, true)) {
            return;
        }

    }

}
