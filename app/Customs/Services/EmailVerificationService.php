<?php
namespace App\Customs\Services;

use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerifiedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmailVerificationService
{
    public function sendVerificationLink($user): void
    {
        Notification::send($user, new EmailVerifiedNotification($this->generateVerificationLink($user->email)));
    }

    public function generateVerificationLink($email):string
    {
        $checkIfTokenExists = EmailVerificationToken::where("email", $email)->first();
        if($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();
        $url = config("app.url")."?token=".$token."&email=".$email;
        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token"=> $token,
            "experied_at" => now()->addMinutes(5)
            ]);
            if ($saveToken) {
                return $url;
            }
    }
}