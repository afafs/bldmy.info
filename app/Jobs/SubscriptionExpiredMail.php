<?php

namespace App\Jobs;

use App\Http\Helpers\MegaMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $bs;
    public $be;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $bs, $be)
    {
        $this->user = $user;
        $this->bs = $bs;
        $this->be = $be;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $this->user->email,
            'toName' => $this->user->first_name,
            'username' => $this->user->username,
            'login_link' => "<a href='" . route('user.login') . "'>" . route('user.login') . "</a>",
            'website_title' => $this->bs->website_title,
            'templateType' => 'membership_expired',
            'type' => 'membershipExpired'
        ];
        $mailer->mailFromAdmin($data);
    }
}
