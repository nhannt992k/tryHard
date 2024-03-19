<?php
namespace App\Customs\Services;

// use App\Models\EmailVerificationToken;
// use App\Notifications\EmailVerifiedNotification;
// use Illuminate\Support\Facades\Notification;
// use Illuminate\Support\Str;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationService
{
//     public function sendVerificationLink($user): void
//     {
//         Notification::send($user, new EmailVerifiedNotification($this->generateVerificationLink($user->email)));
//     }

//     public function generateVerificationLink($email):string
//     {
//         $checkIfTokenExists = EmailVerificationToken::where("email", $email)->first();
//         if($checkIfTokenExists) $checkIfTokenExists->delete();
//         $token = Str::uuid();
//         $url = config("app.url")."?token=".$token."&email=".$email;
//         $saveToken = EmailVerificationToken::create([
//             "email" => $email,
//             "token"=> $token,
//             "experied_at" => now()->addMinutes(5)
//             ]);
//             if ($saveToken) {
//                 return $url;
//             }
//     }
public function verifyEmail(Request $request, $id, $hash)
{
    $user = User::find($id);

    if (! $user || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid verification link',
        ], Response::HTTP_BAD_REQUEST);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Email already verified',
        ],Response::HTTP_OK);
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }
    return redirect('/views/email-verified-successfully');
}
}