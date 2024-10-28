<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class RegistrationController extends Controller
{
    /**
     * Register User
     *
     * @param RegistrationRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate a 4-digit verification code
        $verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Create the user with verification code and no email verified date
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'verification_code' => $verificationCode,
            'email_verified_at' => null,
        ]);

        // Send verification email with the code
        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json(['message' => 'Please check your email for the verification code.']);
    }
}
