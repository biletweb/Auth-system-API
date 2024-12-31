<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmEmailRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            event(new Registered($user));

            DB::commit();

            return response()->json([
                'message' => 'Account has been created successfully! Please confirm your email. We have sent a verification code to the address you provided.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to create account, try again later.']);
        }
    }

    public function login(LoginRequest $request)
    {
        if (auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'You have successfully logged in.',
                'user' => auth()->user()->only('id', 'name', 'surname', 'email', 'email_verified_at', 'role', 'locale'),
                'access_token' => auth()->user()->createToken('authToken')->plainTextToken,
            ]);
        } else {
            return response()->json([
                'field' => 'email, password',
                'error' => 'Invalid credentials.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have successfully logged out.',
        ]);
    }

    public function confirmEmail(ConfirmEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['warning' => 'You have already verified your email.']);
        }

        if ($request->verification_code !== $request->user()->verification_code) {
            return response()->json(['error' => 'Invalid verification code.']);
        }

        $request->user()->markEmailAsVerified();
        $request->user()->update([
            'verification_code' => null,
        ]);

        return response()->json(['message' => 'You have successfully verified your email.']);
    }

    public function resendEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification code has been sent.']);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            DB::beginTransaction();

            $newPassword = random_int(100000, 999999);
            $user = User::where('email', $request->email)->first();
            $user->password = bcrypt($newPassword);
            $user->save();

            Mail::send('emails.forgot-password', ['name' => $user->name, 'newPassword' => $newPassword], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset password');
            });

            DB::commit();

            return response()->json(['message' => 'A new password has been sent to the email address you provided.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to send email with new password, try again later.']);
        }
    }
}
