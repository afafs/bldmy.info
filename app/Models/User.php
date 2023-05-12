<?php

namespace App\Models;

use App\Models\User\Category;
use App\Models\User\FormInput;
use App\Http\Controllers\Controller;
use App\Models\User\AppointmentBooking;
use App\Models\User\UserDay;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserHoliday;
use App\Models\User\UserOfflinePaymentGateway;
use App\Models\User\UserPaymentGateway;
use App\Notifications\UserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'photo',
        'username',
        'password',
        'phone',
        'city',
        'state',
        'address',
        'country',
        'status',
        'featured',
        'verification_link',
        'email_verified',
        'online_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function memberships()
    {
        return $this->hasMany('App\Models\Membership', 'user_id');
    }

    public function user_custom_domains()
    {
        return $this->hasMany('App\Models\User\UserCustomDomain', 'user_id');
    }

    public function permissions()
    {
        return $this->hasOne('App\Models\User\UserPermission', 'user_id');
    }

    public function basic_setting()
    {
        return $this->hasOne('App\Models\User\BasicSetting', 'user_id');
    }

    public function portfolios()
    {
        return $this->hasMany('App\Models\User\Portfolio', 'user_id');
    }

    public function portfolioCategories()
    {
        return $this->hasMany('App\Models\User\PortfolioCategory', 'user_id');
    }

    public function skills()
    {
        return $this->hasMany('App\Models\User\Skill', 'user_id');
    }

    public function achievements()
    {
        return $this->hasMany('App\Models\User\Achievement', 'user_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\User\UserService', 'user_id');
    }

    public function seos()
    {
        return $this->hasMany('App\Models\User\SEO', 'user_id');
    }

    public function testimonials()
    {
        return $this->hasMany('App\Models\User\UserTestimonial', 'user_id');
    }

    public function blogs()
    {
        return $this->hasMany('App\Models\User\Blog', 'user_id');
    }

    public function blog_categories()
    {
        return $this->hasMany('App\Models\User\BlogCategory', 'user_id');
    }

    public function social_media()
    {
        return $this->hasMany('App\Models\User\Social', 'user_id');
    }

    public function job_experiences()
    {
        return $this->hasMany('App\Models\User\JobExperience', 'user_id');
    }

    public function educations()
    {
        return $this->hasMany('App\Models\User\Education', 'user_id');
    }

    public function permission()
    {
        return $this->hasOne('App\Models\User\UserPermission', 'user_id');
    }

    public function languages()
    {
        return $this->hasMany('App\Models\User\Language', 'user_id');
    }

    public function home_page_texts()
    {
        return $this->hasMany('App\Models\User\HomePageText', 'user_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $username = User::query()->where('email', request()->email)->pluck('username')->first();
        $subject = 'You are receiving this email because we received a password reset request for your account.';
        $body = "Recently you tried forget password for your account.Click below to reset your account password.
             <br>
             <a href='" . url('password/reset/' . $token . '/email/' . request()->email) . "'><button type='button' class='btn btn-primary'>Reset Password</button></a>
             <br>
             Thank you.
             ";
        $controller = new Controller();
        $controller->resetPasswordMail(request()->email, $username, $subject, $body);
        session()->flash('success', "we sent you an email. Please check your inbox");
    }

    public function custom_domains()
    {
        return $this->hasMany('App\Models\User\UserCustomDomain');
    }

    public function cvs()
    {
        return $this->hasMany('App\Models\User\UserCv');
    }

    public function qr_codes()
    {
        return $this->hasMany('App\Models\User\UserQrCode');
    }

    public function vcards()
    {
        return $this->hasMany('App\Models\User\UserVcard');
    }


    public function appointments()
    {
        return $this->hasMany(AppointmentBooking::class);
    }
    public function appointment_categories()
    {
        return $this->hasMany(Category::class);
    }

    public function form_inputs()
    {
        return $this->hasMany(FormInput::class);
    }
    public function email_templates()
    {
        return $this->hasMany(UserEmailTemplate::class);
    }
    public function holidays()
    {
        return $this->hasMany(UserHoliday::class);
    }
    public function days()
    {
        return $this->hasMany(UserDay::class);
    }
    public function offline_payment_gateways()
    {
        return $this->hasMany(UserOfflinePaymentGateway::class);
    }
    public function payment_gateways()
    {
        return $this->hasMany(UserPaymentGateway::class);
    }
}
