<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{ public function sendEmail()
    {
        $data = [
            'title' => 'Tiêu đề email',
            'body' => 'Nội dung email'
        ];
        Mail::send('welcome', $data, function($message) {
            $message->to('nguoidung@domain.com', 'Tên người nhận')
                    ->subject('Tiêu đề email');
        });

        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again later.');
        } else {
            return response()->success('Great! Successfully sent email');
        }
    }
}
