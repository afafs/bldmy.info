<?php

namespace App\Jobs;

use App\Http\Helpers\MegaMailer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $bs;
    public $be;
    public $exDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $bs, $be, $exDate)
    {
        $this->user = $user;
        $this->bs = $bs;
        $this->be = $be;
        $this->exDate = $exDate;
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
            'last_day_of_membership' => Carbon::parse($this->exDate)->toFormattedDateString(),
            'login_link' => "<a href='" . route('user.login') . "'>" . route('user.login') . "</a>",
            'website_title' => $this->bs->website_title,
            'templateType' => 'membership_expiry_reminder',
            'type' => 'membershipExpiryReminder'
        ];
        $mailer->mailFromAdmin($data);
    }
}
