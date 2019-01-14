<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Email;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Log::error('Something went wrong', [
            'person' => ['id' =>(string) 123, 'username' => 'John Doe', 'email' => 'john@doe.com']
        ]);
        return view('home');
    }

    public function email(Request $request)
    {
        $input = $request->all();
        $email = new Email();
        $mail_data = [
            'to'=> $input['email'],
            'subject' => 'Invitation with ICS file',
        ];

        $email->sendMailWithICSFile($mail_data);

        return null;
    }
}
