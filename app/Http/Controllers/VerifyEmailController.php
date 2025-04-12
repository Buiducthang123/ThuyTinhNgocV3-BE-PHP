<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function sendEmailVerificationNotification(Request $request)
    {
        // Mail::to(Auth::user())->send(new VerifyEmail(Auth::user()));
        // $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Email xác thực đã được gửi']);
    }

    public function verifyEmail(Request $request)
    {
        if (! URL::hasValidSignature($request)) {
            return redirect()->away(config('app.frontend_url') . '/email/verified?isOk=0');
        }

        $user = $this->userService->show($request->id,[]);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        if ($user->email_verified_at) {
            return redirect()->away(config('app.frontend_url') . '/email/verified?isOk=1');
        }
        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();
        return redirect()->away(config('app.frontend_url') . '/email/verified?isOk=1');
    }
}
