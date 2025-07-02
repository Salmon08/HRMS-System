<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\EmailTemplate;
use App\Mail\GenericTemplateMail;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;

class AuthController extends Controller
{

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:50',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|confirmed|min:6',
    //     ]);

    //     // Create user first
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Activity log
    //     activity()
    //         ->causedBy($user)
    //         ->log('User registered');

    //     // Assign role
    //     $user->assignRole('user');

    //     // Send mail inside try
    //     try {
    //         Mail::to($user->email)->send(new WelcomeMail($user));
    //     } catch (\Exception $e) {
    //         logger()->error('Mail failed: ' . $e->getMessage());
    //     }

    //     return response()->json(['redirect' => 'login']);
    // }


    public function login(Request $request)
    {
         $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
             $request->session()->regenerate();
            $user = Auth::user();



            // activity()
            //     ->causedBy($user)
            //     ->tap(function ($activity) {
            //         $activity->description = 'User logged in';
            //     })
            //     ->log('User logged in'); // this will now correctly save description

            //     $template = EmailTemplate::where('name', 'user_login')->first();

            // if ($template) {
            //     // Replace placeholders with actual values
            //     $replacements = [
            //         '{{name}}'  => $user->name,
            //         '{{email}}' => $user->email,
            //         '{{date}}'  => $user->created_at->setTimezone('Asia/Kolkata')->format('d M Y h:i A'),
            //     ];

            //     $content = str_replace(array_keys($replacements), array_values($replacements), $template->body);

            //     try {
            //         Mail::to($user->email)->send(new GenericTemplateMail($template->subject, $content));
            //     } catch (\Exception $e) {
            //         logger()->error('Login mail failed: ' . $e->getMessage());
            //     }
            // }

            // return response()->json(['redirect' => '/products']);
            $template = EmailTemplate::where('name', 'user_login')->first();

            if ($template) {
                // Replace placeholders with actual values
                $replacements = [
                    '{{name}}'  => $user->name,
                    '{{email}}' => $user->email,
                    '{{date}}'  => $user->created_at->setTimezone('Asia/Kolkata')->format('d M Y h:i A'),
                ];

                $content = str_replace(array_keys($replacements), array_values($replacements), $template->body);

                try {
                    Mail::to($user->email)->send(new GenericTemplateMail($template->subject, $content));
                } catch (\Exception $e) {
                    logger()->error('Login mail failed: ' . $e->getMessage());
                }
        }


             return response()->json(['redirect' => '/employees']);
    }
    return response()->json(['message' => 'Invalid credentials'], 422);
}


public function firebaseLogin(Request $request)
{
    try {
        $firebase = (new Factory)->withServiceAccount(storage_path('app/json/firebase.json'))->createAuth();
        $verifiedIdToken = $firebase->verifyIdToken($request->token);
        $uid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $firebase->getUser($uid);

        $user = User::where('email', $firebaseUser->email)->first();

        if ($user) {
            // Update name if needed, but do NOT overwrite password
            $user->name = $firebaseUser->displayName ?? $firebaseUser->email;
            $user->save();
        } else {
            // Create new user with random password
            $user = User::create([
                'email' => $firebaseUser->email,
                'name' => $firebaseUser->displayName ?? $firebaseUser->email,
                'password' => bcrypt(Str::random(16)),
            ]);
        }

        Auth::login($user);

        if (!$user->roles()->exists()) {
            $user->assignRole('employee');
        }

        return response()->json(['redirect' => '/employees']);
    } catch (\Throwable $e) {
        logger()->error('Firebase login failed: ' . $e->getMessage());
        return response()->json(['message' => 'Firebase authentication failed'], 401);
    }
}

    // public function firebaseLogin(Request $request)
    // {
    //     try {
    //         $firebase = (new Factory)->withServiceAccount(storage_path('app/json/firebase.json'))->createAuth();
    //         $verifiedIdToken = $firebase->verifyIdToken($request->token);
    //         $uid = $verifiedIdToken->claims()->get('sub');

    //         $firebaseUser = $firebase->getUser($uid);

    //         $user = User::updateOrCreate(
    //             ['email' => $firebaseUser->email],
    //             [
    //                 'name' => $firebaseUser->displayName ?? $firebaseUser->email,
    //                 'password' => bcrypt(Str::random(16)),
    //             ]
    //         );

    //         Auth::login($user);

    //         activity()->causedBy($user)->tap(fn($a) => $a->description = 'User logged in via Google')->log('User logged in via Google');

    //         if (!$user->roles()->exists()) {
    //             $user->assignRole('user');
    //         }

    //         $template = EmailTemplate::where('name', 'user_login')->first();
    //         if ($template) {
    //             $replacements = [
    //                 '{{name}}' => $user->name,
    //                 '{{email}}' => $user->email,
    //                 '{{date}}' => now()->setTimezone('Asia/Kolkata')->format('d M Y h:i A'),
    //             ];
    //             $content = str_replace(array_keys($replacements), array_values($replacements), $template->body);

    //             try {
    //                 Mail::to($user->email)->send(new GenericTemplateMail($template->subject, $content));
    //             } catch (\Exception $e) {
    //                 logger()->error('Google login mail failed: ' . $e->getMessage());
    //             }
    //         }

    //         return response()->json(['redirect' => '/products']);
    //     } catch (\Throwable $e) {
    //         logger()->error('Firebase login failed: ' . $e->getMessage());
    //         return response()->json(['message' => 'Firebase authentication failed'], 401);
    //     }
    // }

    public function logout()
    {
        $user = Auth::user();

        // activity()
        //     ->causedBy($user)
        //     ->tap(function ($activity) {
        //         $activity->description = 'User logged out';
        //     })
        //     ->log('User logged out'); // this will now correctly save description

        Auth::logout();
        return redirect('/login');
    }

}
