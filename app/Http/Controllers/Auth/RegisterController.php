<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{


    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::TOP;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' =>[
                'required',
                'string',
                'min:4',
                'max:12',
                'regex:/^[\p{Han}\p{Katakana}\p{Hiragana}\p{N}\p{P}\p{S}a-zA-Z0-9-_.@+]*$/u'
                ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9\-_.@]+$/u'
                ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'regex:/^[a-zA-Z0-9\-_.@+]*$/u',
            ],
            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ],
        [
            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'name.min' => 'ユーザー名は4文字以上12文字以内で入力してください。',
            'name.max' => 'ユーザー名は4文字以上12文字以内で入力してください。',
            'name.regex' => '名ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',

            'email.required' => 'メールアドレスを入力してください。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => 'メールアドレスは正しい形式で入力してください。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',
            'email.unique' => '入力されたメールアドレスはすでに登録されています。別のメールアドレスを登録してください。',
            'email.regex' => 'メールアドレスには利用可能な文字を入力してください。',

            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.min' => 'パスワードは8文字以上128文字以内で入力してください。',
            'password.max' => 'パスワードは8文字以上128文字以内で入力してください。',
            'password.regex' => 'パスワードには半角英数記号を入力してください。',

            'password_confirmation.required' => '確認用のパスワードを入力してください。',
            'password_confirmation.same' => 'パスワードと確認の入力が一致しません。',
        ]
    );
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => '/storage/image/dawn.png',
            'created_at' => now(),
        ]);
    }
}
