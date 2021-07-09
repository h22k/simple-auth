<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            return $this->successLogin();
        }

        $this->setFlash('Veritabanı bağlantı hatası!');
        return redirect(route('admin.register'));

    }


    /**
     * @param AuthRequest $request
     * @return \Illuminate\Http\RedirectResponse|Redirector
     */
    public function login(AuthRequest $request) : Redirector | RedirectResponse
    {

        $credentials = [
            //Email varlığını request sınıfında kontrol ettiğim için burada etmeme gerek yok.
            'email' => $this->data['email'],
            'password' => $this->data['password']
        ];

        if (\Auth::attempt($credentials, true)) {

            return $this->successLogin();

        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors([
                'password' => 'E-posta ya da parola yanlış!'
            ]);

    }

    /**
     * @return Redirector
     */
    public function successLogin() : Redirector
    {

        $this->setFlash('Başarıyla giriş yapıldı!');
        return redirect(route('admin.home'));

    }

    public function logout(Request $request)
    {

        \Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('admin.login'));


    }


}
