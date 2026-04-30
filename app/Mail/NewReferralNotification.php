<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReferralNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $referrer;
    public $referral;

    public function __construct(User $referrer, User $referral)
    {
        $this->referrer = $referrer;
        $this->referral = $referral;
    }

    public function build()
    {
        return $this->subject('🎉 Nouveau parrainage sur VisaFly !')
                    ->view('emails.new_referral');
    }
}