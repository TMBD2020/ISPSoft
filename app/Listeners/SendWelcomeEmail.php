<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
class SendWelcomeEmail
{

    public function __construct()
    {
        //
    }

    public function handle(UserRegistered $event)
    {
        $data = array('name' => $event->user->name, 'email' => $event->user->email, 'body' => 'Welcome to our website. Hope you will enjoy our articles');

        Mail::send('emails.welcome', $data, function($message) use ($data) {
            $message->to($data['email'])
                ->subject('Welcome to our Website');
            $message->from('info@techmakersbd.com');
        });
    }
}
