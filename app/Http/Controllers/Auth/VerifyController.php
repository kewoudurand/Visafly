<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;


class VerifyController extends Controller
{
    public function form()
    {
        return view('verify');
    }

    public function check(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'code'=>'required'
        ]);

        $user = User::where('email',$request->email)
                    ->where('verification_code',$request->code)
                    ->first();

        if(!$user){
            return back()->withErrors(['code'=>'Code invalide']);
        }

        $user->update([
            'email_verified_at'=>now(),
            'verification_code'=>null
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Bienvenue sur VisaFly, ' . $user->first_name . ' !');
    }
}