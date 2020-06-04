<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStudentEmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updateEmail(UpdateStudentEmailRequest $request)
    {
        $user = $request->user();
        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'email' => $request->email
            ]);

            return back()->with('result',  ['status' => 'success', 'message' => 'Email успешно изменен']);
        }

        return back()->with('result',  ['status' => 'failure', 'message' => 'Неверный пароль']);
    }
}
