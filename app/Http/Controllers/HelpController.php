<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class HelpController extends Controller
{

    public function loginVerification(Request $request) {
        $login = $request->login;
        $user = User::where("login", "=", $login)->first();
        if(empty($user)) {
            return "true";
        }
       return "false";
    }
}
