<?php

namespace App\Http\Controllers;

use App\Mail\SignUpEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SingUpEmail;

class SendMailController extends Controller
{
    public function sendSignUpEmail(Request $request)
    {
        $email = $request->email;
        $data = array(
            'name' => $request->name,
            'email' => $request->email
        );
        // Mail::send('mail/index', $data, function ($mail) use ($email) {
        //     $mail->to($email, 'no-reply')
        //         ->subject("Rajapala Caffe");
        //     $mail->from($email, 'Rajapala Caffe');
        // });

        Mail::to($email)->send(new SignUpEmail);

        if (Mail::failures()) {
            return "gagal mengirim email";
        }
        return "email berhasil dikirim";
    }
}
