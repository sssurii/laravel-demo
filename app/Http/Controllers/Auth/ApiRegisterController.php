<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class ApiRegisterController extends RegisterController
{
    /**
     * Handle a registration request for the application.
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $data = $request->all();
        $errors = $this->validator($data)->errors();

        if (count($errors)) {
            return response(['errors' => $errors], 401);
        }
        event(new Registered($user = $this->create($data)));

        $this->guard()->login($user);

        return response(['user' => $user]);
    }
}