<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\PasswordReset;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use App\Mail\GenericTemplateMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return Inertia::render('Password/ForgetPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We couldn\'t find a user with that email address.']);
        }

        $token = Str::random(64);
        $uuid = Str::uuid();

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'uuid' => $uuid,
                'created_at' => now(),
                'used' => false,
            ]
        );

        $link = url("/reset-password/{$uuid}");

         $template = EmailTemplate::where('name', 'reset_password')->first();

         $content = str_replace(
        ['{{name}}', '{{reset_link}}', '{{app_name}}'],
        [$user->name, $link, config('app.name')],
        $template->body
    );

        // Mail::raw("Reset your password: $link", function ($message) use ($request) {
        //     $message->to($request->email)
        //             ->subject('Reset Your Password');
        // });
        // Mail::to($request->email)->send(new ResetPasswordMail($resetUrl));

        Mail::to($user->email)->send(new GenericTemplateMail($template->subject, $content));

        return back()->with('status', 'Reset link sent to your email!');
    }


    public function showResetForm($uuid)
    {
        $resetRecord = DB::table('password_resets')->where('uuid', $uuid)->first();

    if (!$resetRecord) {
        return Inertia::render('ResetPassword', [
            'error' => 'Invalid reset link.',
            'showForm' => false,
        ]);
    }

    // Used link
    if (!empty($resetRecord->used)) {
        return Inertia::render('ResetPassword', [
            'error' => 'This reset link has already been used.',
            'showForm' => false,
        ]);
    }

    // Expired link
    if (now()->diffInMinutes($resetRecord->created_at) > 60) {
        return Inertia::render('ResetPassword', [
            'error' => 'This reset link has expired.',
            'showForm' => false,
        ]);
    }

         return Inertia::render('ResetPassword', [
            'uuid' => $uuid,
            'token' => $resetRecord->token,
            'showForm' => true,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'uuid' => 'required',
            'token' => 'required',
            // 'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Find the password reset record by token and email
        $resetRecord = \DB::table('password_resets')
            ->where('uuid', $request->uuid)
            // ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        // Token not found
        if (!$resetRecord) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        // Token already used
        if (!empty($resetRecord->used)) {
            return back()->withErrors(['token' => 'This reset link has already been used.']);
        }

        // Check expiry (optional, here 60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            return back()->withErrors(['token' => 'This reset link has expired.']);
        }

        // Try to find the user
        $user = User::where('email', $resetRecord->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found for the provided email.']);
        }

        // Reset password
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));

        // 🔒 Mark this reset token as used
        \DB::table('password_resets')
            ->where('uuid', $request->uuid)
            // ->where('email', $request->email)
            // ->where('token', $request->token)
            ->update(['used' => true]);

        return redirect()->route('login')->with('status', 'Password has been reset successfully.');
    }
}
