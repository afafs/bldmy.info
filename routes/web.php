<?php

$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

Route::fallback(function () {
    return view('errors.404');
});

Route::domain($domain)->group(function () {

    Route::get('/changelanguage/{lang}', 'Front\FrontendController@changeLanguage')->name('changeLanguage');

    // cron job for sending expiry mail
    Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
    Route::post('/push', 'Front\PushController@store');

    Route::group(['middleware' => 'setlang'], function () {
        Route::get('/', 'Front\FrontendController@index')->name('front.index');
        Route::post('/subscribe', 'Front\FrontendController@subscribe')->name('front.subscribe');
        Route::get('/users', 'Front\FrontendController@users')->name('front.user.view');
        Route::get('/contact', 'Front\FrontendController@contactView')->name('front.contact');
        Route::get('/faqs', 'Front\FrontendController@faqs')->name('front.faq.view');
        Route::get('/blogs', 'Front\FrontendController@blogs')->name('front.blogs');
        Route::get('/pricing', 'Front\FrontendController@pricing')->name('front.pricing');
        Route::get('/blog/{slug}/{id}', 'Front\FrontendController@blogdetails')->name('front.blogdetails');
        Route::get('/registration/step-1/{status}/{id}', 'Front\FrontendController@step1')->name('front.register.view');
        Route::get('/check/{username}/username', 'Front\FrontendController@checkUsername')->name('front.username.check');
        Route::get('/p/{slug}', 'Front\FrontendController@dynamicPage')->name('front.dynamicPage');
        Route::view('/success', 'front.success')->name('success.page');
    });

    Route::group(['middleware' => ['web', 'guest', 'setlang']], function () {
        Route::get('/registration/final-step', 'Front\FrontendController@step2')->name('front.registration.step2');
        Route::post('/checkout', 'Front\FrontendController@checkout')->name('front.checkout.view');

        Route::get('/login', 'User\Auth\LoginController@showLoginForm')->name('user.login');
        Route::post('/login', 'User\Auth\LoginController@login')->name('user.login.submit');
        Route::get('/register', 'User\Auth\RegisterController@registerPage')->name('user-register');
        Route::post('/register/submit', 'User\Auth\RegisterController@register')->name('user-register-submit');
        Route::get('/register/mode/{mode}/verify/{token}', 'User\Auth\RegisterController@token')->name('user-register-token');

        Route::post('/password/email', 'User\Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.forgot.password.submit');
        Route::get('/password/reset', 'User\Auth\ForgotPasswordController@showLinkRequestForm')->name('user.forgot.password.form');
        Route::post('/password/reset', 'User\Auth\ResetPasswordController@reset')->name('user.reset.password.submit');
        Route::get('/password/reset/{token}/email/{email}', 'User\Auth\ResetPasswordController@showResetForm')->name('user.reset.password.form');

        Route::get('/forgot', 'User\ForgotController@showforgotform')->name('user-forgot');
        Route::post('/forgot', 'User\ForgotController@forgot')->name('user-forgot-submit');
    });

    /*=======================================================
    ******************** User Dashboard Routes **********************
    =======================================================*/

    Route::group(['prefix' => 'user', 'middleware' => ['auth', 'userstatus']], function () {
        // user theme change
        Route::get('/change-theme', 'User\UserController@changeTheme')->name('user.theme.change');
        // RTL check
        Route::get('/rtlcheck/{langid}', 'User\LanguageController@rtlcheck')->name('user.rtlcheck');

        Route::get('/dashboard', 'User\UserController@index')->name('user-dashboard');
        Route::get('/reset', 'User\UserController@resetform')->name('user-reset');
        Route::post('/reset', 'User\UserController@reset')->name('user-reset-submit');
        Route::get('/profile', 'User\UserController@profile')->name('user-profile');
        Route::post('/profile', 'User\UserController@profileupdate')->name('user-profile-update');
        Route::get('/logout', 'User\Auth\LoginController@logout')->name('user-logout');
        Route::post('/change-status', 'User\UserController@status')->name('user-status');

        // Payment Log
        Route::get('/payment-log', 'User\PaymentLogController@index')->name('user.payment-log.index');

        // User Domains & URLs
        Route::group(['middleware' => 'checkUserPermission:Custom Domain'], function () {
            Route::get('/domains', 'User\DomainController@domains')->name('user-domains');
            Route::post('/request/domain', 'User\DomainController@domainrequest')->name('user-domain-request');
        });

        // User Subdomains & URLs
        Route::get('/subdomain', 'User\SubdomainController@subdomain')->name('user-subdomain');

        //user follow and following list
        Route::group(['middleware' => 'checkUserPermission:Follow/Unfollow'], function () {
            Route::get('/follower-list', 'User\FollowerController@follower')->name('user.follower.list');
            Route::get('/following-list', 'User\FollowerController@following')->name('user.following.list');
            Route::get('/follow/{id}', 'User\FollowerController@follow')->name('user.follow');
            Route::get('/unfollow/{id}', 'User\FollowerController@unfollow')->name('user.unfollow');
        });

        // User vCard routes
        Route::group(['middleware' => 'checkUserPermission:vCard'], function () {
            Route::get('/vcard', 'User\VcardController@vcard')->name('user.vcard');
            Route::get('/vcard/create', 'User\VcardController@create')->name('user.vcard.create');
            Route::post('/vcard/store', 'User\VcardController@store')->name('user.vcard.store');
            Route::get('/vcard/{uservcard}/edit', 'User\VcardController@edit')->name('user.vcard.edit');
            Route::post('/vcard/update', 'User\VcardController@update')->name('user.vcard.update');
            Route::post('/vcard/delete', 'User\VcardController@delete')->name('user.vcard.delete');
            Route::post('/vcard/bulk/delete', 'User\VcardController@bulkDelete')->name('user.vcard.bulk.delete');
            Route::get('/vcard/{id}/information', 'User\VcardController@information')->name('user.vcard.information');

            Route::get('/vcard/{uservcard}/services', 'User\VcardController@services')->name('user.vcard.services');
            Route::post('/vcard/service/store', 'User\VcardController@serviceStore')->name('user.vcard.serviceStore');
            Route::post('/vcard/service/update', 'User\VcardController@serviceUpdate')->name('user.vcard.serviceUpdate');
            Route::post('/vcard/service/delete', 'User\VcardController@serviceDelete')->name('user.vcard.serviceDelete');
            Route::post('/vcard/bulk/service/delete', 'User\VcardController@bulkServiceDelete')->name('user.vcard.bulkServiceDelete');

            Route::get('/vcard/{id}/projects', 'User\VcardController@projects')->name('user.vcard.projects');
            Route::post('/vcard/project/store', 'User\VcardController@projectStore')->name('user.vcard.projectStore');
            Route::post('/vcard/project/update', 'User\VcardController@projectUpdate')->name('user.vcard.projectUpdate');
            Route::post('/vcard/project/delete', 'User\VcardController@projectDelete')->name('user.vcard.projectDelete');
            Route::post('/vcard/bulk/project/delete', 'User\VcardController@bulkProjectDelete')->name('user.vcard.bulkProjectDelete');

            Route::get('/vcard/{id}/testimonials', 'User\VcardController@testimonials')->name('user.vcard.testimonials');
            Route::post('/vcard/testimonial/store', 'User\VcardController@testimonialStore')->name('user.vcard.testimonialStore');
            Route::post('/vcard/testimonial/update', 'User\VcardController@testimonialUpdate')->name('user.vcard.testimonialUpdate');
            Route::post('/vcard/testimonial/delete', 'User\VcardController@testimonialDelete')->name('user.vcard.testimonialDelete');
            Route::post('/vcard/bulk/testimonial/delete', 'User\VcardController@bulkTestimonialDelete')->name('user.vcard.bulkTestimonialDelete');

            Route::get('/vcard/{id}/about', 'User\VcardController@about')->name('user.vcard.about');
            Route::post('/vcard/aboutUpdate', 'User\VcardController@aboutUpdate')->name('user.vcard.aboutUpdate');

            Route::get('/vcard/{id}/preferences', 'User\VcardController@preferences')->name('user.vcard.preferences');
            Route::post('/vcard/{id}/prefUpdate', 'User\VcardController@prefUpdate')->name('user.vcard.prefUpdate');

            Route::get('/vcard/{id}/color', 'User\VcardController@color')->name('user.vcard.color');
            Route::post('/vcard/{id}/colorUpdate', 'User\VcardController@colorUpdate')->name('user.vcard.colorUpdate');

            Route::get('/vcard/{id}/keywords', 'User\VcardController@keywords')->name('user.vcard.keywords');
            Route::post('/vcard/{id}/keywordsUpdate', 'User\VcardController@keywordsUpdate')->name('user.vcard.keywordsUpdate');
        });

        // User CVs Routes
        Route::group(['middleware' => 'checkUserPermission:Online CV & Export'], function () {
            Route::get('/cv', 'User\CvController@cv')->name('user.cv');
            Route::post('/cv/store', 'User\CvController@store')->name('user.cv.store');
            Route::get('/cv/{cv}/edit', 'User\CvController@edit')->name('user.cv.edit');
            Route::post('/cv/update', 'User\CvController@update')->name('user.cv.update');
            Route::post('/cv/delete', 'User\CvController@delete')->name('user.cv.delete');
            Route::post('/cv/bulk/delete', 'User\CvController@bulkDelete')->name('user.cv.bulk.delete');
            Route::get('/cv/{cv}/info', 'User\CvController@info')->name('user.cv.info');
            Route::get('/cv/{id}/getinfos', 'User\CvController@getinfos')->name('user.cv.getinfos');
            Route::post('/cv/update/information', 'User\CvController@updateInfo')->name('user.cv.update.info');
            Route::post('/cv/update/contact_title', 'User\CvController@updateContactTitle')->name('user.cv.contact_title');


            Route::get('/cv/{cv}/sections', 'User\CvSectionController@index')->name('user.cv.section.index');
            Route::post('/section/store', 'User\CvSectionController@store')->name('user.cv.section.store');
            Route::get('/section/{id}/edit', 'User\CvSectionController@edit')->name('user.cv.section.edit');
            Route::get('/section/{id}/content', 'User\CvSectionController@content')->name('user.cv.section.content');
            Route::post('/section/update', 'User\CvSectionController@update')->name('user.cv.section.update');
            Route::post('/section/delete', 'User\CvSectionController@delete')->name('user.cv.section.delete');
            Route::post('/section/order', 'User\CvSectionController@orderUpdate')->name('user.cv.section.order');
            Route::get('/cv/section/{id}/content', 'User\CvSectionController@content')->name('user.cv.section.content');
            Route::get('/cv/section/{id}/getcontents', 'User\CvSectionController@getcontents')->name('user.cv.section.getcontents');
            Route::post('/cv/section/update/content', 'User\CvSectionController@updateContent')->name('user.cv.section.update.content');
        });

        // user QR Builder
        Route::group(['middleware' => 'checkUserPermission:QR Builder'], function () {
            Route::get('/saved/qrs', 'User\QrController@index')->name('user.qrcode.index');
            Route::post('/saved/qr/delete', 'User\QrController@delete')->name('user.qrcode.delete');
            Route::post('/saved/qr/bulk-delete', 'User\QrController@bulkDelete')->name('user.qrcode.bulk.delete');
            Route::get('/qr-code', 'User\QrController@qrCode')->name('user.qrcode');
            Route::post('/qr-code/generate', 'User\QrController@generate')->name('user.qrcode.generate');
            Route::get('/qr-code/clear', 'User\QrController@clear')->name('user.qrcode.clear');
            Route::post('/qr-code/save', 'User\QrController@save')->name('user.qrcode.save');
        });

        // user Appointment

        Route::group(['prefix' => 'appointment'], function () {
            Route::get('/setting', 'User\AppointmentController@setting')->name('user.appointment.setting');
            Route::post('/update-appointment-setting', 'User\AppointmentController@updateSetting')->name('user.appointment.category.control');

            // time slot start
            Route::get('/time-slot', 'User\AppointmentController@timeSlot')->name('user.appointment.timeslot');
            Route::get('/time-slot/management', 'User\AppointmentController@timeslotManagement')->name('user.timeslot.management');
            Route::post('/time-slot/store', 'User\AppointmentController@timeslotStore')->name('user.timeslot.store');
            Route::post('/time-slot/update', 'User\AppointmentController@timeslotUpdate')->name('user.timeslot.update');
            Route::post('/time-slot/delete', 'User\AppointmentController@timeslotDelete')->name('user.timeslot.delete');

            // making Weekend 
            Route::post('/time-slot/make-weekend', 'User\AppointmentController@makeWeekend')->name('user.makeWeekend');
            // time slot end

            // booked appointment start 
            Route::get('/all', 'User\AppointmentController@appointments')->name('user.bookedAppointment');
            Route::get('/pending', 'User\AppointmentController@appointments')->name('user.pendingAppointment');
            Route::get('/approved', 'User\AppointmentController@appointments')->name('user.approvedAppointment');
            Route::get('/completed', 'User\AppointmentController@appointments')->name('user.completedAppointment');
            Route::get('/rejected', 'User\AppointmentController@appointments')->name('user.rejectedAppointment');
            Route::post('appointment/status/update', 'User\AppointmentController@chnageStatus')->name('user.change.appointment.status');
            Route::post('appointment/payment-status/update', 'User\AppointmentController@chnagePaymentStatus')->name('user.change.appointment.payment');
            Route::post('appointment/delete/{appointment}', 'User\AppointmentController@deleteAppointment')->name('user.appointment.delete');
            Route::post('/bulk-delete-appointment', 'User\AppointmentController@appointmentBulkDestroy')->name('user.bulk-delete-appointment');
            Route::get('/details/{appointment}', 'User\AppointmentController@appointmentDetails')->name('user.appointment.view');
            Route::get('/edit/{appointment}', 'User\AppointmentController@appointmentEdit')->name('user.appointment.edit');
            Route::post('/update/{appointment}', 'User\AppointmentController@appointmentUpdate')->name('user.appointment.update');
            // booked appointment end
            // holidays start
            Route::get('/holidays', 'User\AppointmentController@holidays')->name('user.holidays');
            Route::post('/holidays/store', 'User\AppointmentController@holidayStore')->name('user.holiday.store');
            Route::post('/holidays/delete', 'User\AppointmentController@holidayDelete')->name('user.holiday.delete');
            // holidays end

            // category crud
            Route::get('/categories', 'User\CategoryController@category')->name('user.appointment.category');
            Route::post('/categories/store', 'User\CategoryController@categoryStore')->name('user.category.store');
            Route::post('/categories/delete/{category}', 'User\CategoryController@categoryDelete')->name('user.category.delete');
            Route::post('/bulk-delete-category', 'User\CategoryController@categoryBulkDestroy')->name('user.bulk-delete-category');
            Route::post('/get-all-languages', 'User\CategoryController@getalllanguages')->name('user.category.get-language');

            //user Category and forms
            Route::get('/form-builder/{id?}', 'User\CategoryController@form')->name('user.forminput');
            Route::post('/form/store', 'User\FormController@formstore')->name('user.form.store');
            Route::post('/form/inputDelete', 'User\FormController@inputDelete')->name('user.form.inputDelete');
            Route::get('/form/{id}/inputEdit', 'User\FormController@inputEdit')->name('user.form.inputEdit');
            Route::get('/quote/{id}/options', 'User\FormController@options')->name('user.form.options');
            Route::post('/form/inputUpdate', 'User\FormController@inputUpdate')->name('user.form.inputUpdate');
            Route::post('/form/orderUpdate', 'User\FormController@orderUpdate')->name('user.form.orderUpdate');

            Route::get('/reset-serial', 'User\AppointmentController@resetSerial')->name('user.reset-serial-number');
        });

        // user start register-user, ban user, details, reports 

        Route::post('user/customer/ban', 'User\UserController@userban')->name('user.customer.ban');
        Route::get('register/customer/details/{customer}', 'User\UserController@view')->name('register.customer.view');
        Route::post('register/customer/email', 'User\UserController@emailStatus')->name('register.customer.email');
        Route::get('/ads-reports', 'User\PostController@viewReports')->name('user.ads-report');
        Route::get('/register-user', 'User\UserController@registerUsers')->name('user.register-user');
        Route::get('register/customer/{customer}/changePassword', 'User\UserController@changePassCstmr')->name('register.customer.changePass');
        Route::post('register/customer/updatePassword', 'User\UserController@updatePasswordCstmr')->name('register.customer.updatePassword');
        Route::post('register/customer/delete', 'User\UserController@delete')->name('register.customer.delete');
        Route::post('register/customer/bulkdelete', 'User\UserController@bulkdelete')->name('register.customer.bulk.delete');

        // user end register-user, ban user, details, reports 

        // user email 
        Route::get('/mail_templates', 'User\MailTemplateController@mailTemplates')->name('user.basic_settings.mail_templates');
        Route::get('/edit_mail_template/{id}', 'User\MailTemplateController@editMailTemplate')->name('user.basic_settings.edit_mail_template');
        Route::post('/update_mail_template/{id}', 'User\MailTemplateController@updateMailTemplate')->name('user.basic_settings.update_mail_template');
        Route::get('/mail/information/subscriber', 'User\SubscriberController@getMailInformation')->name('user.mail.information');
        Route::post('/mail/information/subscriber', 'User\SubscriberController@storeMailInformation')->name('user.mail.subscriber');

        Route::get('/change-password', 'User\UserController@changePass')->name('user.changePass');
        Route::post('/profile/updatePassword', 'User\UserController@updatePassword')->name('user.updatePassword');

        //user language
        Route::get('/languages', 'User\LanguageController@index')->name('user.language.index');
        Route::get('/language/{language}/edit', 'User\LanguageController@edit')->name('user.language.edit');
        Route::get('/language/{language}/edit/keyword', 'User\LanguageController@editKeyword')->name('user.language.editKeyword');
        Route::post('/language/{id}/update/keyword', 'User\LanguageController@updateKeyword')->name('user.language.updateKeyword');
        Route::post('/language/store', 'User\LanguageController@store')->name('user.language.store');
        Route::post('/language/upload', 'User\LanguageController@upload')->name('user.language.upload');
        Route::post('/language/{id}/uploadUpdate', 'User\LanguageController@uploadUpdate')->name('user.language.uploadUpdate');
        Route::post('/language/{id}/default', 'User\LanguageController@default')->name('user.language.default');
        Route::post('/language/{id}/delete', 'User\LanguageController@delete')->name('user.language.delete');
        Route::post('/add-keyword', 'User\LanguageController@addKeyword')->name('user.language_management.add_keyword');
        Route::post('/language/update', 'User\LanguageController@update')->name('user.language.update');

        //user color
        Route::get('color', 'User\ColorController@index')->name('user.color.index');
        Route::post('color/update', 'User\ColorController@update')->name('user.color.update');

        //user preference
        Route::get('preference', 'User\PreferenceController@index')->name('user.preference.index');
        Route::post('preference/update', 'User\PreferenceController@update')->name('user.preference.update');

        // basic settings general-settings route
        Route::get('/basic-settings/general-settings', 'User\BasicSettingController@information')->name('user.basic_settings.general-settings');
        Route::post('/basic-settings/update-info', 'User\BasicSettingController@updateInfo')->name('user.basic_settings.update_info');


        Route::get('/theme/version', 'User\BasicController@themeVersion')->name('user.theme.version');
        Route::post('/theme/update_version', 'User\BasicController@updateThemeVersion')->name('user.theme.update');

        //user favicon routes
        Route::get('/favicon', 'User\BasicController@favicon')->name('user.favicon');
        Route::post('/favicon/post', 'User\BasicController@updatefav')->name('user.favicon.update');


        // user logo routes

        Route::get('/logo', 'User\BasicController@logo')->name('user.logo');
        Route::post('/logo/post', 'User\BasicController@updatelogo')->name('user.logo.update');

        // user preloader routes

        Route::get('/preloader', 'User\BasicController@preloader')->name('user.preloader');
        Route::post('/preloader/post', 'User\BasicController@updatepreloader')->name('user.preloader.update');


        // basic settings plugins route start
        Route::get('/plugins', 'User\BasicController@plugins')->name('user.plugins');
        Route::post('/update-analytics', 'User\BasicController@updateAnalytics')->name('user.update_analytics');
        Route::post('/update-whatsapp', 'User\BasicController@updateWhatsApp')->name('user.update_whatsapp');
        Route::post('/update-disqus', 'User\BasicController@updateDisqus')->name('user.update_disqus');
        Route::post('/update-pixel', 'User\BasicController@updatePixel')->name('user.update_pixel');
        Route::post('/update-tawkto', 'User\BasicController@updateTawkto')->name('user.update_tawkto');
        // basic settings plugins route end


        // basic settings seo route
        Route::get('/basic_settings/seo', 'User\BasicController@seo')->name('admin.basic_settings.seo');
        Route::post('/basic_settings/update_seo_informations', 'User\BasicController@updateSEO')->name('admin.basic_settings.update_seo_informations');

        // user cv upload routes

        Route::get('/cv-upload', 'User\BasicController@cvUpload')->name('user.cv.upload');
        Route::post('/cv-upload/update', 'User\BasicController@updateCV')->name('user.cv.upload.update');
        Route::post('/cv-upload/delete', 'User\BasicController@deleteCV')->name('user.cv.upload.delete');

        // user home page routes
        Route::get('/home-page-text/edit', 'User\BasicController@homePageTextEdit')->name('user.home.page.text.edit');
        Route::post('/home-page-text/update', 'User\BasicController@homePageTextUpdate')->name('user.home.page.text.update');
        Route::post('/home-image-remove', 'User\BasicController@homeImageRemove')->name('user.home.image.remove');

        // user Social routes
        Route::get('/social', 'User\SocialController@index')->name('user.social.index');
        Route::post('/social/store', 'User\SocialController@store')->name('user.social.store');
        Route::get('/social/{social}/edit', 'User\SocialController@edit')->name('user.social.edit');
        Route::post('/social/update', 'User\SocialController@update')->name('user.social.update');
        Route::post('/social/delete', 'User\SocialController@delete')->name('user.social.delete');


        Route::group(['middleware' => 'checkUserPermission:Blog'], function () {
            //user blog categories
            Route::get('/blog-categories', 'User\BlogCategoryController@index')->name('user.blog.category.index');
            Route::post('/blog-category/store', 'User\BlogCategoryController@store')->name('user.blog.category.store');
            Route::post('/blog-category/update', 'User\BlogCategoryController@update')->name('user.blog.category.update');
            Route::post('/blog-category/delete', 'User\BlogCategoryController@delete')->name('user.blog.category.delete');
            Route::post('/blog-category/bulk-delete', 'User\BlogCategoryController@bulkDelete')->name('user.blog.category.bulk.delete');

            //user blogs
            Route::get('/blogs', 'User\BlogController@index')->name('user.blog.index');
            Route::post('/blog/upload', 'User\BlogController@upload')->name('user.blog.upload');
            Route::post('/blog/store', 'User\BlogController@store')->name('user.blog.store');
            Route::get('/blog/{blog}/edit', 'User\BlogController@edit')->name('user.blog.edit');
            Route::post('/blog/update', 'User\BlogController@update')->name('user.blog.update');
            Route::post('/blog/{id}/uploadUpdate', 'User\BlogController@uploadUpdate')->name('user.blog.uploadUpdate');
            Route::post('/blog/delete', 'User\BlogController@delete')->name('user.blog.delete');
            Route::post('/blog/bulk-delete', 'User\BlogController@bulkDelete')->name('user.blog.bulk.delete');
            Route::get('/blog/{langid}/getcats', 'User\BlogController@getcats')->name('user.blog.getcats');
        });

        // Summernote image upload
        Route::post('/summernote/upload', 'Admin\SummernoteController@upload')->name('user.summernote.upload');


        //user skills
        Route::group(['middleware' => 'checkUserPermission:Skill'], function () {
            Route::get('/skills', 'User\SkillController@index')->name('user.skill.index');
            Route::post('/skill/upload', 'User\SkillController@upload')->name('user.skill.upload');
            Route::post('/skill/store', 'User\SkillController@store')->name('user.skill.store');
            Route::get('/skill/{skill}/edit', 'User\SkillController@edit')->name('user.skill.edit');
            Route::post('/skill/update', 'User\SkillController@update')->name('user.skill.update');
            Route::post('/skill/{id}/uploadUpdate', 'User\SkillController@uploadUpdate')->name('user.skill.uploadUpdate');
            Route::post('/skill/delete', 'User\SkillController@delete')->name('user.skill.delete');
            Route::post('/skill/bulk-delete', 'User\SkillController@bulkDelete')->name('user.skill.bulk.delete');
        });

        //user achievements
        Route::group(['middleware' => 'checkUserPermission:Achievements'], function () {
            Route::get('/achievements', 'User\AchievementController@index')->name('user.achievement.index');
            Route::post('/achievement/store', 'User\AchievementController@store')->name('user.achievement.store');
            Route::get('/achievement/{achievement}/edit', 'User\AchievementController@edit')->name('user.achievement.edit');
            Route::post('/achievement/update', 'User\AchievementController@update')->name('user.achievement.update');
            Route::post('/achievement/delete', 'User\AchievementController@delete')->name('user.achievement.delete');
            Route::post('/achievement/bulk-delete', 'User\AchievementController@bulkDelete')->name('user.achievement.bulk.delete');
        });


        Route::group(['middleware' => 'checkUserPermission:Portfolio'], function () {
            //user portfolio categories
            Route::get('/portfolio-categories', 'User\PortfolioCategoryController@index')->name('user.portfolio.category.index');
            Route::post('/portfolio-category/store', 'User\PortfolioCategoryController@store')->name('user.portfolio.category.store');
            Route::post('/portfolio-category/update', 'User\PortfolioCategoryController@update')->name('user.portfolio.category.update');
            Route::post('/portfolio-category/delete', 'User\PortfolioCategoryController@delete')->name('user.portfolio.category.delete');
            Route::post('/portfolio-category/bulk-delete', 'User\PortfolioCategoryController@bulkDelete')->name('user.portfolio.category.bulk.delete');

            //user portfolios

            Route::get('/portfolios', 'User\PortfolioController@index')->name('user.portfolio.index');
            Route::post('/portfolio/store', 'User\PortfolioController@store')->name('user.portfolio.store');
            Route::post('/portfolio/sliderstore', 'User\PortfolioController@sliderstore')->name('user.portfolio.sliderstore');
            Route::post('/portfolio/sliderupdate', 'User\PortfolioController@sliderupdate')->name('user.portfolio.sliderupdate');
            Route::post('/portfolio/sliderrmv', 'User\PortfolioController@sliderrmv')->name('user.portfolio.sliderrmv');
            Route::get('/portfolio/{portfolio}/edit', 'User\PortfolioController@edit')->name('user.portfolio.edit');
            Route::get('/portfolio/{id}/images', 'User\PortfolioController@images')->name('user.portfolio.images');
            Route::post('/portfolio/sliderupdate', 'User\PortfolioController@sliderupdate')->name('user.portfolio.sliderupdate');
            Route::post('/portfolio/update', 'User\PortfolioController@update')->name('user.portfolio.update');
            Route::post('/portfolio/delete', 'User\PortfolioController@delete')->name('user.portfolio.delete');
            Route::post('/portfolio/bulk-delete', 'User\PortfolioController@bulkDelete')->name('user.portfolio.bulk.delete');
            Route::get('/portfolio/{langid}/getcats', 'User\PortfolioController@getcats')->name('user.portfolio.getcats');
        });

        Route::group(['middleware' => 'checkUserPermission:Service'], function () {
            //Services
            Route::get('/services', 'User\ServiceController@index')->name('user.services.index');
            Route::post('/service/store', 'User\ServiceController@store')->name('user.service.store');
            Route::get('/service/{service}/edit', 'User\ServiceController@edit')->name('user.service.edit');
            Route::post('/service/update', 'User\ServiceController@update')->name('user.service.update');
            Route::post('/service/delete', 'User\ServiceController@delete')->name('user.service.delete');
            Route::post('/service/bulk-delete', 'User\ServiceController@bulkDelete')->name('user.service.bulk.delete');
        });

        //Testimonial
        Route::group(['middleware' => 'checkUserPermission:Testimonial'], function () {
            Route::get('/testimonials', 'User\TestimonialController@index')->name('user.testimonials.index');
            Route::post('/testimonial/store', 'User\TestimonialController@store')->name('user.testimonial.store');
            Route::get('/testimonial/{testimonial}/edit', 'User\TestimonialController@edit')->name('user.testimonial.edit');
            Route::post('/testimonial/update', 'User\TestimonialController@update')->name('user.testimonial.update');
            Route::post('/testimonial/delete', 'User\TestimonialController@delete')->name('user.testimonial.delete');
            Route::post('/testimonial/bulk-delete', 'User\TestimonialController@bulkDelete')->name('user.testimonial.bulk.delete');
        });

        //user experience categories
        Route::group(['middleware' => 'checkUserPermission:Experience'], function () {
            Route::get('/experience-categories', 'User\SubExperienceController@index')->name('user.experience.category.index');
            Route::post('/experience-category/store', 'User\SubExperienceController@store')->name('user.experience.category.store');
            Route::post('/experience-category/update', 'User\SubExperienceController@update')->name('user.experience.category.update');
            Route::post('/experience-category/delete', 'User\SubExperienceController@delete')->name('user.experience.category.delete');
            Route::post('/experience-category/bulk-delete', 'User\SubExperienceController@bulkDelete')->name('user.experience.category.bulk.delete');

            //user job experience

            Route::get('/job-experiences', 'User\JobExperienceController@index')->name('user.job.experiences.index');
            Route::post('/job-experience/store', 'User\JobExperienceController@store')->name('user.job.experience.store');
            Route::get('/job-experience/{experience}/edit', 'User\JobExperienceController@edit')->name('user.job.experience.edit');
            Route::post('/job-experience/update', 'User\JobExperienceController@update')->name('user.job.experience.update');
            Route::post('/job-experience/delete', 'User\JobExperienceController@delete')->name('user.job.experience.delete');
            Route::post('/job-experience/bulk-delete', 'User\JobExperienceController@bulkDelete')->name('user.job.experience.bulk.delete');

            //user educational experiences

            Route::get('/experiences', 'User\EducationController@index')->name('user.experience.index');
            Route::post('/experience/upload', 'User\EducationController@upload')->name('user.experience.upload');
            Route::post('/experience/store', 'User\EducationController@store')->name('user.experience.store');
            Route::get('/experience/{education}/edit', 'User\EducationController@edit')->name('user.experience.edit');
            Route::post('/experience/update', 'User\EducationController@update')->name('user.experience.update');
            Route::post('/experience/{id}/uploadUpdate', 'User\EducationController@uploadUpdate')->name('user.experience.uploadUpdate');
            Route::post('/experience/delete', 'User\EducationController@delete')->name('user.experience.delete');
            Route::post('/experience/bulk-delete', 'User\EducationController@bulkDelete')->name('user.experience.bulk.delete');
        });
        //user package extend route
        Route::get('/package-list', 'User\BuyPlanController@index')->name('user.plan.extend.index');
        Route::get('/package/checkout/{package_id}', 'User\BuyPlanController@checkout')->name('user.plan.extend.checkout');
        Route::post('/package/checkout', 'User\UserCheckoutController@checkout')->name('user.plan.checkout');

        // User Online Gateways Routes
        Route::get('/gateways', 'User\GatewayController@index')->name('user.gateway.index');
        Route::post('/stripe/update', 'User\GatewayController@stripeUpdate')->name('user.stripe.update');
        Route::post('/anet/update', 'User\GatewayController@anetUpdate')->name('user.anet.update');
        Route::post('/paypal/update', 'User\GatewayController@paypalUpdate')->name('user.paypal.update');
        Route::post('/paystack/update', 'User\GatewayController@paystackUpdate')->name('user.paystack.update');
        Route::post('/paytm/update', 'User\GatewayController@paytmUpdate')->name('user.paytm.update');
        Route::post('/flutterwave/update', 'User\GatewayController@flutterwaveUpdate')->name('user.flutterwave.update');
        Route::post('/instamojo/update', 'User\GatewayController@instamojoUpdate')->name('user.instamojo.update');
        Route::post('/mollie/update', 'User\GatewayController@mollieUpdate')->name('user.mollie.update');
        Route::post('/razorpay/update', 'User\GatewayController@razorpayUpdate')->name('user.razorpay.update');
        Route::post('/mercadopago/update', 'User\GatewayController@mercadopagoUpdate')->name('user.mercadopago.update');

        // User Offline Gateway Routes
        Route::get('/offline/gateways', 'User\GatewayController@offline')->name('user.gateway.offline');
        Route::post('/offline/gateway/store', 'User\GatewayController@store')->name('user.gateway.offline.store');
        Route::post('/offline/gateway/update', 'User\GatewayController@update')->name('user.gateway.offline.update');
        Route::post('/offline/status', 'User\GatewayController@status')->name('user.offline.status');
        Route::post('/offline/gateway/delete', 'User\GatewayController@delete')->name('user.offline.gateway.delete');
    });

    /*=======================================================
    ******************** Admin Dashboard Routes **********************
    =======================================================*/

    Route::group(['prefix' => 'admin', 'middleware' => ['guest:admin']], function () {
        Route::get('/', 'Admin\LoginController@login')->name('admin.login');
        Route::post('/login', 'Admin\LoginController@authenticate')->name('admin.auth');
        Route::get('/mail-form', 'Admin\ForgetController@mailForm')->name('admin.forget.form');
        Route::post('/sendmail', 'Admin\ForgetController@sendmail')->name('admin.forget.mail');
    });

    Route::get('/set-locale-admin', 'Admin\BasicController@setLocaleAdmin')->name('set-Locale-admin');

    Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checkstatus', 'adminLang']], function () {

        // RTL check
        Route::get('/rtlcheck/{langid}', 'Admin\LanguageController@rtlcheck')->name('admin.rtlcheck');

        // admin redirect to dashboard route
        Route::get('/change-theme', 'Admin\DashboardController@changeTheme')->name('admin.theme.change');

        // Summernote image upload
        Route::post('/summernote/upload', 'Admin\SummernoteController@upload')->name('admin.summernote.upload');

        // Admin logout Route
        Route::get('/logout', 'Admin\LoginController@logout')->name('admin.logout');

        Route::group(['middleware' => ['checkpermission:Dashboard']], function () {
            // Admin Dashboard Routes
            Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('admin.dashboard');
        });


        // Admin Profile Routes
        Route::get('/changePassword', 'Admin\ProfileController@changePass')->name('admin.changePass');
        Route::post('/profile/updatePassword', 'Admin\ProfileController@updatePassword')->name('admin.updatePassword');
        Route::get('/profile/edit', 'Admin\ProfileController@editProfile')->name('admin.editProfile');
        Route::post('/profile/update', 'Admin\ProfileController@updateProfile')->name('admin.updateProfile');


        Route::group(['middleware' => 'checkpermission:Settings'], function () {

            // Admin Favicon Routes
            Route::get('/favicon', 'Admin\BasicController@favicon')->name('admin.favicon');
            Route::post('/favicon/post', 'Admin\BasicController@updatefav')->name('admin.favicon.update');

            // Admin Logo Routes
            Route::get('/logo', 'Admin\BasicController@logo')->name('admin.logo');
            Route::post('/logo/post', 'Admin\BasicController@updatelogo')->name('admin.logo.update');

            // Admin Preloader Routes
            Route::get('/preloader', 'Admin\BasicController@preloader')->name('admin.preloader');
            Route::post('/preloader/post', 'Admin\BasicController@updatepreloader')->name('admin.preloader.update');

            // Admin Basic Information Routes
            Route::get('/basicinfo', 'Admin\BasicController@basicinfo')->name('admin.basicinfo');
            Route::post('/basicinfo/post', 'Admin\BasicController@updatebasicinfo')->name('admin.basicinfo.update');


            // Admin Email Settings Routes
            Route::get('/mail-from-admin', 'Admin\EmailController@mailFromAdmin')->name('admin.mailFromAdmin');
            Route::post('/mail-from-admin/update', 'Admin\EmailController@updateMailFromAdmin')->name('admin.mailfromadmin.update');
            Route::get('/mail-to-admin', 'Admin\EmailController@mailToAdmin')->name('admin.mailToAdmin');
            Route::post('/mail-to-admin/update', 'Admin\EmailController@updateMailToAdmin')->name('admin.mailtoadmin.update');
            Route::get('/mail_templates', 'Admin\MailTemplateController@mailTemplates')->name('admin.mail_templates');
            Route::get('/edit_mail_template/{id}', 'Admin\MailTemplateController@editMailTemplate')->name('admin.edit_mail_template');
            Route::post('/update_mail_template/{id}', 'Admin\MailTemplateController@updateMailTemplate')->name('admin.update_mail_template');

            // Admin Breadcrumb Routes
            Route::get('/breadcrumb', 'Admin\BasicController@breadcrumb')->name('admin.breadcrumb');
            Route::post('/breadcrumb/update', 'Admin\BasicController@updatebreadcrumb')->name('admin.breadcrumb.update');

            // Admin Scripts Routes
            Route::get('/script', 'Admin\BasicController@script')->name('admin.script');
            Route::post('/script/update', 'Admin\BasicController@updatescript')->name('admin.script.update');


            // Admin Social Routes
            Route::get('/social', 'Admin\SocialController@index')->name('admin.social.index');
            Route::post('/social/store', 'Admin\SocialController@store')->name('admin.social.store');
            Route::get('/social/{id}/edit', 'Admin\SocialController@edit')->name('admin.social.edit');
            Route::post('/social/update', 'Admin\SocialController@update')->name('admin.social.update');
            Route::post('/social/delete', 'Admin\SocialController@delete')->name('admin.social.delete');


            // Admin Maintanance Mode Routes
            Route::get('/maintainance', 'Admin\BasicController@maintainance')->name('admin.maintainance');
            Route::post('/maintainance/update', 'Admin\BasicController@updatemaintainance')->name('admin.maintainance.update');


            // Admin Section Customization Routes
            Route::get('/sections', 'Admin\BasicController@sections')->name('admin.sections.index');
            Route::post('/sections/update', 'Admin\BasicController@updatesections')->name('admin.sections.update');


            // Admin Cookie Alert Routes
            Route::get('/cookie-alert', 'Admin\BasicController@cookiealert')->name('admin.cookie.alert');
            Route::post('/cookie-alert/{langid}/update', 'Admin\BasicController@updatecookie')->name('admin.cookie.update');

            // basic settings seo route
            Route::get('/seo', 'Admin\BasicController@seo')->name('admin.seo');
            Route::post('/seo/update', 'Admin\BasicController@updateSEO')->name('admin.seo.update');
        });


        Route::group(['middleware' => 'checkpermission:Subscribers'], function () {
            // Admin Subscriber Routes
            Route::get('/subscribers', 'Admin\SubscriberController@index')->name('admin.subscriber.index');
            Route::get('/mailsubscriber', 'Admin\SubscriberController@mailsubscriber')->name('admin.mailsubscriber');
            Route::post('/subscribers/sendmail', 'Admin\SubscriberController@subscsendmail')->name('admin.subscribers.sendmail');
            Route::post('/subscriber/delete', 'Admin\SubscriberController@delete')->name('admin.subscriber.delete');
            Route::post('/subscriber/bulk-delete', 'Admin\SubscriberController@bulkDelete')->name('admin.subscriber.bulk.delete');
        });


        Route::group(['middleware' => 'checkpermission:Push Notification'], function () {
            // Admin Push Notification Routes
            Route::get('/pushnotification/settings', 'Admin\PushController@settings')->name('admin.pushnotification.settings');
            Route::post('/pushnotification/update/settings', 'Admin\PushController@updateSettings')->name('admin.pushnotification.updateSettings');
            Route::get('/pushnotification/send', 'Admin\PushController@send')->name('admin.pushnotification.send');
            Route::post('/push', 'Admin\PushController@push')->name('admin.pushnotification.push');
        });


        Route::group(['middleware' => 'checkpermission:Menu Builder'], function () {
            Route::get('/menu-builder', 'Admin\MenuBuilderController@index')->name('admin.menu_builder.index');
            Route::post('/menu-builder/update', 'Admin\MenuBuilderController@update')->name('admin.menu_builder.update');
        });


        Route::group(['middleware' => 'checkpermission:Home Page'], function () {

            // Admin Hero Section Image & Text Routes
            Route::get('/herosection/imgtext', 'Admin\HerosectionController@imgtext')->name('admin.herosection.imgtext');
            Route::post('/herosection/{langid}/update', 'Admin\HerosectionController@update')->name('admin.herosection.update');


            // Admin Feature Routes
            Route::get('/features', 'Admin\FeatureController@index')->name('admin.feature.index');
            Route::post('/feature/store', 'Admin\FeatureController@store')->name('admin.feature.store');
            Route::get('/feature/{id}/edit', 'Admin\FeatureController@edit')->name('admin.feature.edit');
            Route::post('/feature/update', 'Admin\FeatureController@update')->name('admin.feature.update');
            Route::post('/feature/delete', 'Admin\FeatureController@delete')->name('admin.feature.delete');


            // Admin Work Process Routes
            Route::get('/process', 'Admin\ProcessController@index')->name('admin.process.index');
            Route::post('/process/store', 'Admin\ProcessController@store')->name('admin.process.store');
            Route::get('/process/{id}/edit', 'Admin\ProcessController@edit')->name('admin.process.edit');
            Route::post('/process/update', 'Admin\ProcessController@update')->name('admin.process.update');
            Route::post('/process/delete', 'Admin\ProcessController@delete')->name('admin.process.delete');

            // Admin Intro Section Routes
            Route::get('/introsection', 'Admin\IntrosectionController@index')->name('admin.introsection.index');
            Route::post('/introsection/{langid}/update', 'Admin\IntrosectionController@update')->name('admin.introsection.update');
            Route::post('/introsection/remove/image', 'Admin\IntrosectionController@removeImage')->name('admin.introsection.img.rmv');


            // Admin Testimonial Routes
            Route::get('/testimonials', 'Admin\TestimonialController@index')->name('admin.testimonial.index');
            Route::get('/testimonial/create', 'Admin\TestimonialController@create')->name('admin.testimonial.create');
            Route::post('/testimonial/store', 'Admin\TestimonialController@store')->name('admin.testimonial.store');
            Route::get('/testimonial/{id}/edit', 'Admin\TestimonialController@edit')->name('admin.testimonial.edit');
            Route::post('/testimonial/update', 'Admin\TestimonialController@update')->name('admin.testimonial.update');
            Route::post('/testimonial/delete', 'Admin\TestimonialController@delete')->name('admin.testimonial.delete');
            Route::post('/testimonialtext/{langid}/update', 'Admin\TestimonialController@textupdate')->name('admin.testimonialtext.update');

            // Admin home page text routes
            Route::get('/home-page-text-section', 'Admin\HomePageTextController@index')->name('admin.home.page.text.index');
            Route::post('/home-page-text-section/{langid}/update', 'Admin\HomePageTextController@update')->name('admin.home.page.text.update');

            // Admin Partner Routes
            Route::get('/partners', 'Admin\PartnerController@index')->name('admin.partner.index');
            Route::post('/partner/store', 'Admin\PartnerController@store')->name('admin.partner.store');
            Route::post('/partner/upload', 'Admin\PartnerController@upload')->name('admin.partner.upload');
            Route::get('/partner/{id}/edit', 'Admin\PartnerController@edit')->name('admin.partner.edit');
            Route::post('/partner/update', 'Admin\PartnerController@update')->name('admin.partner.update');
            Route::post('/partner/{id}/uploadUpdate', 'Admin\PartnerController@uploadUpdate')->name('admin.partner.uploadUpdate');
            Route::post('/partner/delete', 'Admin\PartnerController@delete')->name('admin.partner.delete');
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            // Menu Manager Routes
            Route::get('/pages', 'Admin\PageController@index')->name('admin.page.index');
            Route::get('/page/create', 'Admin\PageController@create')->name('admin.page.create');
            Route::post('/page/store', 'Admin\PageController@store')->name('admin.page.store');
            Route::get('/page/{menuID}/edit', 'Admin\PageController@edit')->name('admin.page.edit');
            Route::post('/page/update', 'Admin\PageController@update')->name('admin.page.update');
            Route::post('/page/delete', 'Admin\PageController@delete')->name('admin.page.delete');
            Route::post('/page/bulk-delete', 'Admin\PageController@bulkDelete')->name('admin.page.bulk.delete');
        });


        Route::group(['middleware' => 'checkpermission:Footer'], function () {
            // Admin Footer Logo Text Routes
            Route::get('/footers', 'Admin\FooterController@index')->name('admin.footer.index');
            Route::post('/footer/{langid}/update', 'Admin\FooterController@update')->name('admin.footer.update');
            Route::post('/footer/remove/image', 'Admin\FooterController@removeImage')->name('admin.footer.rmvimg');


            // Admin Ulink Routes
            Route::get('/ulinks', 'Admin\UlinkController@index')->name('admin.ulink.index');
            Route::get('/ulink/create', 'Admin\UlinkController@create')->name('admin.ulink.create');
            Route::post('/ulink/store', 'Admin\UlinkController@store')->name('admin.ulink.store');
            Route::get('/ulink/{id}/edit', 'Admin\UlinkController@edit')->name('admin.ulink.edit');
            Route::post('/ulink/update', 'Admin\UlinkController@update')->name('admin.ulink.update');
            Route::post('/ulink/delete', 'Admin\UlinkController@delete')->name('admin.ulink.delete');
        });


        // Announcement Popup Routes
        Route::group(['middleware' => 'checkpermission:Announcement Popup'], function () {
            Route::get('popups', 'Admin\PopupController@index')->name('admin.popup.index');
            Route::get('popup/types', 'Admin\PopupController@types')->name('admin.popup.types');
            Route::get('popup/{id}/edit', 'Admin\PopupController@edit')->name('admin.popup.edit');
            Route::get('popup/create', 'Admin\PopupController@create')->name('admin.popup.create');
            Route::post('popup/store', 'Admin\PopupController@store')->name('admin.popup.store');;
            Route::post('popup/delete', 'Admin\PopupController@delete')->name('admin.popup.delete');
            Route::post('popup/bulk-delete', 'Admin\PopupController@bulkDelete')->name('admin.popup.bulk.delete');
            Route::post('popup/status', 'Admin\PopupController@status')->name('admin.popup.status');
            Route::post('popup/update', 'Admin\PopupController@update')->name('admin.popup.update');;
        });


        Route::group(['middleware' => ['checkpermission:Registered Users', 'adminLang']], function () {
            // Register User start
            Route::get('register/users', 'Admin\RegisterUserController@index')->name('admin.register.user');
            Route::post('register/user/store', 'Admin\RegisterUserController@store')->name('register.user.store');
            Route::post('admin//chnage/user/online/status/{id}', 'Admin\RegisterUserController@changeOnlineStatus')->name('user.online.status.change');
            Route::post('register/users/ban', 'Admin\RegisterUserController@userban')->name('register.user.ban');
            Route::post('register/users/featured', 'Admin\RegisterUserController@userFeatured')->name('register.user.featured');
            Route::post('register/users/template', 'Admin\RegisterUserController@userTemplate')->name('register.user.template');
            Route::post('register/users/template/update', 'Admin\RegisterUserController@userUpdateTemplate')->name('register.user.updateTemplate');
            Route::post('register/users/email', 'Admin\RegisterUserController@emailStatus')->name('register.user.email');
            Route::get('register/user/details/{id}', 'Admin\RegisterUserController@view')->name('register.user.view');
            Route::post('/user/current-package/remove', 'Admin\RegisterUserController@removeCurrPackage')->name('user.currPackage.remove');
            Route::post('/user/current-package/change', 'Admin\RegisterUserController@changeCurrPackage')->name('user.currPackage.change');
            Route::post('/user/current-package/add', 'Admin\RegisterUserController@addCurrPackage')->name('user.currPackage.add');
            Route::post('/user/next-package/remove', 'Admin\RegisterUserController@removeNextPackage')->name('user.nextPackage.remove');
            Route::post('/user/next-package/change', 'Admin\RegisterUserController@changeNextPackage')->name('user.nextPackage.change');
            Route::post('/user/next-package/add', 'Admin\RegisterUserController@addNextPackage')->name('user.nextPackage.add');
            Route::post('register/user/delete', 'Admin\RegisterUserController@delete')->name('register.user.delete');
            Route::post('register/user/secret-login', 'Admin\RegisterUserController@secretLogin')->name('register.user.secretLogin');
            Route::post('register/user/bulk-delete', 'Admin\RegisterUserController@bulkDelete')->name('register.user.bulk.delete');
            Route::get('register/user/{id}/changePassword', 'Admin\RegisterUserController@changePass')->name('register.user.changePass');
            Route::post('register/user/updatePassword', 'Admin\RegisterUserController@updatePassword')->name('register.user.updatePassword');
            //Register User end

        });


        Route::group(['middleware' => 'checkpermission:FAQ Management'], function () {
            // Admin FAQ Routes
            Route::get('/faqs', 'Admin\FaqController@index')->name('admin.faq.index');
            Route::get('/faq/create', 'Admin\FaqController@create')->name('admin.faq.create');
            Route::post('/faq/store', 'Admin\FaqController@store')->name('admin.faq.store');
            Route::post('/faq/update', 'Admin\FaqController@update')->name('admin.faq.update');
            Route::post('/faq/delete', 'Admin\FaqController@delete')->name('admin.faq.delete');
            Route::post('/faq/bulk-delete', 'Admin\FaqController@bulkDelete')->name('admin.faq.bulk.delete');
        });


        Route::group(['middleware' => ['checkpermission:Blogs', 'adminLang']], function () {
            // Admin Blog Category Routes
            Route::get('/bcategorys', 'Admin\BcategoryController@index')->name('admin.bcategory.index');
            Route::post('/bcategory/store', 'Admin\BcategoryController@store')->name('admin.bcategory.store');
            Route::post('/bcategory/update', 'Admin\BcategoryController@update')->name('admin.bcategory.update');
            Route::post('/bcategory/delete', 'Admin\BcategoryController@delete')->name('admin.bcategory.delete');
            Route::post('/bcategory/bulk-delete', 'Admin\BcategoryController@bulkDelete')->name('admin.bcategory.bulk.delete');


            // Admin Blog Routes
            Route::get('/blogs', 'Admin\BlogController@index')->name('admin.blog.index');
            Route::post('/blog/upload', 'Admin\BlogController@upload')->name('admin.blog.upload');
            Route::post('/blog/store', 'Admin\BlogController@store')->name('admin.blog.store');
            Route::get('/blog/{id}/edit', 'Admin\BlogController@edit')->name('admin.blog.edit');
            Route::post('/blog/update', 'Admin\BlogController@update')->name('admin.blog.update');
            Route::post('/blog/{id}/uploadUpdate', 'Admin\BlogController@uploadUpdate')->name('admin.blog.uploadUpdate');
            Route::post('/blog/delete', 'Admin\BlogController@delete')->name('admin.blog.delete');
            Route::post('/blog/bulk-delete', 'Admin\BlogController@bulkDelete')->name('admin.blog.bulk.delete');
            Route::get('/blog/{langid}/getcats', 'Admin\BlogController@getcats')->name('admin.blog.getcats');
        });


        Route::group(['middleware' => 'checkpermission:Sitemap'], function () {

            Route::get('/sitemap', 'Admin\SitemapController@index')->name('admin.sitemap.index');
            Route::post('/sitemap/store', 'Admin\SitemapController@store')->name('admin.sitemap.store');
            Route::get('/sitemap/{id}/update', 'Admin\SitemapController@update')->name('admin.sitemap.update');
            Route::post('/sitemap/{id}/delete', 'Admin\SitemapController@delete')->name('admin.sitemap.delete');
            Route::post('/sitemap/download', 'Admin\SitemapController@download')->name('admin.sitemap.download');
        });


        Route::group(['middleware' => 'checkpermission:Contact Page'], function () {
            // Admin Contact Routes
            Route::get('/contact', 'Admin\ContactController@index')->name('admin.contact.index');
            Route::post('/contact/{langid}/post', 'Admin\ContactController@update')->name('admin.contact.update');
        });


        Route::group(['middleware' => 'checkpermission:Payment Gateways'], function () {
            // Admin Online Gateways Routes
            Route::get('/gateways', 'Admin\GatewayController@index')->name('admin.gateway.index');
            Route::post('/stripe/update', 'Admin\GatewayController@stripeUpdate')->name('admin.stripe.update');
            Route::post('/anet/update', 'Admin\GatewayController@anetUpdate')->name('admin.anet.update');
            Route::post('/paypal/update', 'Admin\GatewayController@paypalUpdate')->name('admin.paypal.update');
            Route::post('/paystack/update', 'Admin\GatewayController@paystackUpdate')->name('admin.paystack.update');
            Route::post('/paytm/update', 'Admin\GatewayController@paytmUpdate')->name('admin.paytm.update');
            Route::post('/flutterwave/update', 'Admin\GatewayController@flutterwaveUpdate')->name('admin.flutterwave.update');
            Route::post('/instamojo/update', 'Admin\GatewayController@instamojoUpdate')->name('admin.instamojo.update');
            Route::post('/mollie/update', 'Admin\GatewayController@mollieUpdate')->name('admin.mollie.update');
            Route::post('/razorpay/update', 'Admin\GatewayController@razorpayUpdate')->name('admin.razorpay.update');
            Route::post('/mercadopago/update', 'Admin\GatewayController@mercadopagoUpdate')->name('admin.mercadopago.update');

            // Admin Offline Gateway Routes
            Route::get('/offline/gateways', 'Admin\GatewayController@offline')->name('admin.gateway.offline');
            Route::post('/offline/gateway/store', 'Admin\GatewayController@store')->name('admin.gateway.offline.store');
            Route::post('/offline/gateway/update', 'Admin\GatewayController@update')->name('admin.gateway.offline.update');
            Route::post('/offline/status', 'Admin\GatewayController@status')->name('admin.offline.status');
            Route::post('/offline/gateway/delete', 'Admin\GatewayController@delete')->name('admin.offline.gateway.delete');
        });
        Route::group(['middleware' => 'checkpermission:Role Management'], function () {
            // Admin Roles Routes
            Route::get('/roles', 'Admin\RoleController@index')->name('admin.role.index');
            Route::post('/role/store', 'Admin\RoleController@store')->name('admin.role.store');
            Route::post('/role/update', 'Admin\RoleController@update')->name('admin.role.update');
            Route::post('/role/delete', 'Admin\RoleController@delete')->name('admin.role.delete');
            Route::get('role/{id}/permissions/manage', 'Admin\RoleController@managePermissions')->name('admin.role.permissions.manage');
            Route::post('role/permissions/update', 'Admin\RoleController@updatePermissions')->name('admin.role.permissions.update');
        });
        Route::group(['middleware' => 'checkpermission:Admins Management'], function () {
            // Admin Users Routes
            Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
            Route::post('/user/upload', 'Admin\UserController@upload')->name('admin.user.upload');
            Route::post('/user/store', 'Admin\UserController@store')->name('admin.user.store');
            Route::get('/user/{id}/edit', 'Admin\UserController@edit')->name('admin.user.edit');
            Route::post('/user/update', 'Admin\UserController@update')->name('admin.user.update');
            Route::post('/user/{id}/uploadUpdate', 'Admin\UserController@uploadUpdate')->name('admin.user.uploadUpdate');
            Route::post('/user/delete', 'Admin\UserController@delete')->name('admin.user.delete');
        });
        Route::group(['middleware' => 'checkpermission:Language Management'], function () {
            // Admin Language Routes
            Route::get('/languages', 'Admin\LanguageController@index')->name('admin.language.index');
            Route::get('/language/{id}/edit', 'Admin\LanguageController@edit')->name('admin.language.edit');
            Route::get('/language/{id}/edit/keyword', 'Admin\LanguageController@editKeyword')->name('admin.language.editKeyword');
            Route::get('/language/{id}/edit/admin-keyword', 'Admin\LanguageController@editAdminKeyword')->name('admin.language.editAdminKeyword');
            Route::post('/language/store', 'Admin\LanguageController@store')->name('admin.language.store');
            Route::post('/language/upload', 'Admin\LanguageController@upload')->name('admin.language.upload');
            Route::post('/language/{id}/uploadUpdate', 'Admin\LanguageController@uploadUpdate')->name('admin.language.uploadUpdate');
            Route::post('/language/{id}/default', 'Admin\LanguageController@default')->name('admin.language.default');
            Route::post('/language/{id}/delete', 'Admin\LanguageController@delete')->name('admin.language.delete');
            Route::post('/language/update', 'Admin\LanguageController@update')->name('admin.language.update');
            Route::post('/language/{id}/update/keyword', 'Admin\LanguageController@updateKeyword')->name('admin.language.updateKeyword');
            Route::get('/user-language/keywords', 'Admin\LanguageController@userlanguagekeywords')->name('admin.userlanguage.keywords');
            Route::get('/user-language/settings', 'Admin\LanguageController@userlanguageSettings')->name('admin.userlanguage.settings');
            Route::post('/user-language/update', 'Admin\LanguageController@userlanguageupdate')->name('admin.userlanguage.update');
            Route::post('/user-language/update/keyword', 'Admin\LanguageController@updateUserKeyword')->name('admin.userlanguage.updateKeyword');
            Route::post('/add-keyword', 'Admin\LanguageController@addKeyword')->name('admin.language_management.add_keyword');
            Route::post('/add-frontend-keyword', 'Admin\LanguageController@addFrontKeyword')->name('admin.language_management.add_front_keyword');

            Route::post('/user/add-keyword', 'Admin\LanguageController@useraddKeyword')->name('admin.userlanguage.add_keyword');
        });
        // Admin Cache Clear Routes
        Route::get('/cache-clear', 'Admin\CacheController@clear')->name('admin.cache.clear');
        Route::group(['middleware' => 'checkpermission:Packages'], function () {
            // Package Settings routes
            Route::get('/package/settings', 'Admin\PackageController@settings')->name('admin.package.settings');
            Route::post('/package/settings/update', 'Admin\PackageController@updateSettings')->name('admin.package.settings.update');
            // Package Settings routes
            Route::get('/package/features', 'Admin\PackageController@features')->name('admin.package.features');
            Route::post('/package/features/update', 'Admin\PackageController@updateFeatures')->name('admin.package.features.update');
            // Package routes
            Route::get('packages', 'Admin\PackageController@index')->name('admin.package.index');
            Route::post('package/upload', 'Admin\PackageController@upload')->name('admin.package.upload');
            Route::post('package/store', 'Admin\PackageController@store')->name('admin.package.store');
            Route::get('package/{id}/edit', 'Admin\PackageController@edit')->name('admin.package.edit');
            Route::post('package/update', 'Admin\PackageController@update')->name('admin.package.update');
            Route::post('package/{id}/uploadUpdate', 'Admin\PackageController@uploadUpdate')->name('admin.package.uploadUpdate');
            Route::post('package/delete', 'Admin\PackageController@delete')->name('admin.package.delete');
            Route::post('package/bulk-delete', 'Admin\PackageController@bulkDelete')->name('admin.package.bulk.delete');
            // Admin Coupon Routes
            Route::get('/coupon', 'Admin\CouponController@index')->name('admin.coupon.index');
            Route::post('/coupon/store', 'Admin\CouponController@store')->name('admin.coupon.store');
            Route::get('/coupon/{id}/edit', 'Admin\CouponController@edit')->name('admin.coupon.edit');
            Route::post('/coupon/update', 'Admin\CouponController@update')->name('admin.coupon.update');
            Route::post('/coupon/delete', 'Admin\CouponController@delete')->name('admin.coupon.delete');
            // Admin Coupon Routes End
        });
        Route::group(['middleware' => 'checkpermission:Payment Log'], function () {
            // Payment Log
            Route::get('/payment-log', 'Admin\PaymentLogController@index')->name('admin.payment-log.index');
            Route::post('/payment-log/update', 'Admin\PaymentLogController@update')->name('admin.payment-log.update');
        });
        // Custom Domains
        Route::group(['middleware' => 'checkpermission:Custom Domains'], function () {
            Route::get('/domains', 'Admin\CustomDomainController@index')->name('admin.custom-domain.index');
            Route::get('/domain/texts', 'Admin\CustomDomainController@texts')->name('admin.custom-domain.texts');
            Route::post('/domain/texts', 'Admin\CustomDomainController@updateTexts')->name('admin.custom-domain.texts');
            Route::post('/domain/status', 'Admin\CustomDomainController@status')->name('admin.custom-domain.status');
            Route::post('/domain/mail', 'Admin\CustomDomainController@mail')->name('admin.custom-domain.mail');
            Route::post('/domain/delete', 'Admin\CustomDomainController@delete')->name('admin.custom-domain.delete');
            Route::post('/domain/bulk-delete', 'Admin\CustomDomainController@bulkDelete')->name('admin.custom-domain.bulk.delete');
        });
        // Subdomains
        Route::group(['middleware' => 'checkpermission:Subdomains'], function () {
            Route::get('/subdomains', 'Admin\SubdomainController@index')->name('admin.subdomain.index');
            Route::post('/subdomain/status', 'Admin\SubdomainController@status')->name('admin.subdomain.status');
            Route::post('/subdomain/mail', 'Admin\SubdomainController@mail')->name('admin.subdomain.mail');
        });
    });
    Route::group(['middleware' => ['web']], function () {
        Route::post('/coupon', 'Front\CheckoutController@coupon')->name('front.membership.coupon');
        Route::post('/membership/checkout', 'Front\CheckoutController@checkout')->name('front.membership.checkout');
        Route::post('/payment/instructions', 'Front\FrontendController@paymentInstruction')->name('front.payment.instructions');
        Route::post('/admin/contact-msg', 'Front\FrontendController@adminContactMessage')->name('front.admin.contact.message');
        //checkout payment gateway routes
        Route::prefix('membership')->group(function () {
            Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
            Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
            Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
            Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
            Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
            Route::post('mercadopago/cancel', 'Payment\paymenMercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');
            Route::post('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
            Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
            Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
            Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
            Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
            Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');
            Route::post('flutterwave/cancel', 'Payment\FlutterWaveController@cancelPayment')->name('membership.flutterwave.cancel');
            Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
            Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');
            Route::get('anet/cancel', 'Payment\AuthorizenetController@cancelPayment')->name('membership.anet.cancel');
            Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
            Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');
        });
    });
});

$parsedUrl = parse_url(url()->current());
// dd($parsedUrl);
// Output:
// array:3 [▼
//   "scheme" => "http"
//   "host" => "domain.ext"
//   "path" => "/username"
// ]

$host = str_replace("www.", "", $parsedUrl['host']);
if (array_key_exists('host', $parsedUrl)) {
    // if it is a path based URL
    if ($host == env('WEBSITE_HOST')) {
        $domain = $domain;
        $prefix = '/{username}';
    }
    // if it is a subdomain / custom domain
    else {
        if (!app()->runningInConsole()) {
            if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
                $domain = 'www.{domain}';
            } else {
                $domain = '{domain}';
            }
        }
        $prefix = '';
    }
}

Route::group(['domain' => $domain, 'prefix' => $prefix], function () {
 
    Route::get('success',function(){
        return view('user-front.online-success');
    });
    // customer start
    Route::post('/checkout', 'Front\UsercheckoutController@userCheckout')->name('customer.checkout');
    Route::get('/checkout/payment', 'Front\UsercheckoutController@checkoutFinal')->name('customer.payment');
    Route::post('/appointment/checkout', 'Front\UsercheckoutController@checkout')->name('front.user.appointment.checkout');
    // payment for appointment start
    Route::prefix('appointment')->group(function () {
        Route::get('/online/success/{appointment}', 'Front\UsercheckoutController@customerSuccess')->name('customer.success.page');
        Route::get('paypal/success', "User\Payment\PaypalController@successPayment")->name('customer.appointment.paypal.success');
        Route::get('paypal/cancel', "User\Payment\PaypalController@cancelPayment")->name('customer.appointment.paypal.cancel');
        Route::get('stripe/cancel', "User\Payment\StripeController@cancelPayment")->name('customer.appointment.stripe.cancel');
        Route::get('paystack/success', 'User\Payment\PaystackController@successPayment')->name('customer.appointment.paystack.success');
        Route::post('mercadopago/cancel', 'User\Payment\paymenMercadopagoController@cancelPayment')->name('customer.appointment.mercadopago.cancel');
        Route::post('mercadopago/success', 'User\Payment\MercadopagoController@successPayment')->name('customer.appointment.mercadopago.success');
        // Route::get('mercadopago/success/test', 'User\Payment\MercadopagoController@successPayment')->name('customer.appointment.mercadopago.success');
        Route::post('razorpay/success', 'User\Payment\RazorpayController@successPayment')->name('customer.appointment.razorpay.success');
        Route::post('razorpay/cancel', 'User\Payment\RazorpayController@cancelPayment')->name('customer.appointment.razorpay.cancel');
        Route::get('instamojo/success', 'User\Payment\InstamojoController@successPayment')->name('customer.appointment.instamojo.success');
        Route::post('instamojo/cancel', 'User\Payment\InstamojoController@cancelPayment')->name('customer.appointment.instamojo.cancel');
        Route::post('flutterwave/success', 'User\Payment\FlutterWaveController@successPayment')->name('customer.appointment.flutterwave.success');
        Route::post('flutterwave/cancel', 'User\Payment\FlutterWaveController@cancelPayment')->name('customer.appointment.flutterwave.cancel');
        Route::get('/mollie/success', 'User\Payment\MollieController@successPayment')->name('customer.appointment.mollie.success');
        Route::post('mollie/cancel', 'User\Payment\MollieController@cancelPayment')->name('customer.appointment.mollie.cancel');
        Route::get('anet/cancel', 'User\Payment\AuthorizenetController@cancelPayment')->name('customer.appointment.anet.cancel');
        Route::get('/offline/success', 'Front\UsercheckoutController@offlineSuccess')->name('customer.appointment.offline.success');
        Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('customer.appointment.trial.success');
        Route::post('paytm/payment-status', "User\Payment\PaytmController@paymentStatus")->name('customer.appointment.paytm.status');
    });
    // payment for appointment end

    // user redirect to dashboard route
    Route::prefix('/user')->middleware(['auth:customer', 'activeCustomer'])->group(function () {
        // customer checkout start
        Route::get('/dashboard', 'Front\CustomerController@redirectToDashboard')->name('customer.dashboard');
        Route::get('/change-password',  'Front\CustomerController@changePassword')->name('customer.change_password');
        // update password route
        Route::post('/update-password',  'Front\CustomerController@updatePassword')->name('customer.update_password');
        // user logout attempt route
        // edit profile route
        Route::get('/edit-profile', 'Front\CustomerController@editProfile')->name('customer.edit_profile');
        // my appointments route
        Route::get('/my-appointments', 'Front\CustomerController@appointments')->name('customer.appointments');
        Route::get('/appointment-details/{appointment}', 'Front\CustomerController@appointmentDetails')->name('customer.appointments.details');
        // update profile route
        Route::post('/update-profile', 'Front\CustomerController@updateProfile')->name('customer.update_profile');
        Route::get('/logout',  'Front\CustomerController@logoutSubmit')->name('customer.logout');
    });
    Route::prefix('/user')->middleware(['guest:customer'])->group(function () {
        // customer checkout end
        // user redirect to login page route
        Route::get('/login',  'Front\CustomerController@login')->name('customer.login');
        // user login submit route
        Route::post('/login-submit', 'Front\CustomerController@loginSubmit')->name('customer.login_submit');
        // user forget password route
        Route::get('/forget-password', 'Front\CustomerController@forgetPassword')->name('customer.forget_password');
        // send mail to user for forget password route
        Route::post('/send-forget-password-mail', 'Front\CustomerController@sendMail')->name('customer.send_forget_password_mail');
        // reset password route
        Route::get('/reset-password', 'Front\CustomerController@resetPassword')->name('customer.reset_password');
        // user reset password submit route
        Route::post('/reset-password-submit', 'Front\CustomerController@resetPasswordSubmit')->name('customer.reset_password_submit');
        // user redirect to signup page route
        Route::get('/signup', 'Front\CustomerController@signup')->name('customer.signup');
        // user signup submit route
        Route::post('/signup-submit', 'Front\CustomerController@signupSubmit')->name('customer.signup.submit');
        // signup verify route
        Route::get('/signup-verify/{token}', 'Front\CustomerController@signupVerify')->name('customer.signup.verify');
    });
    // customer end
    Route::get('/', 'Front\FrontendController@userDetailView')
        ->name('front.user.detail.view');

    Route::get('/about', 'Front\FrontendController@userAbout')->name('front.user.about');

    Route::group(['middleware' => ['routeAccess:Testimonial']], function () {
        Route::get('/testimonial', 'Front\FrontendController@userTestimonial')->name('front.user.testimonial');
    });

    Route::group(['middleware' => ['routeAccess:Experience']], function () {
        Route::get('/experience', 'Front\FrontendController@userExperience')->name('front.user.experience');
    });

    Route::group(['middleware' => ['routeAccess:Contact']], function () {
        Route::get('/contact', 'Front\FrontendController@userContact')->name('front.user.contact');
        Route::post('/contact/message', 'Front\FrontendController@contactMessage')->name('front.contact.message');
    });
    Route::group(['middleware' => ['routeAccess:Service']], function () {
        Route::get('/services', 'Front\FrontendController@userServices')->name('front.user.services');
        Route::get('/service/{slug}/{id}', 'Front\FrontendController@userServiceDetail')->name('front.user.service.detail');
    });
    Route::group(['middleware' => ['routeAccess:Blog']], function () {
        Route::get('/blogs', 'Front\FrontendController@userBlogs')->name('front.user.blogs');
        Route::get('/blog/{slug}/{id}', 'Front\FrontendController@userBlogDetail')->name('front.user.blog.detail');
    });
    Route::group(['middleware' => ['routeAccess:Appointment']], function () {
        Route::get('/appointment', 'Front\FrontendController@appointment')->name('front.user.appointment');
        Route::get('/appointment/form/{cat?}', 'Front\FrontendController@appointmentForm')->name('front.user.appointment.form');
        Route::post('/appointment/booking/step1', 'Front\FrontendController@appointmentBookingStep1')->name('front.user.appointment.booking.step1');
        Route::get('/appointment/booking/step2', 'Front\FrontendController@appointmentBookingStep2')->name('front.user.appointment.booking');
        Route::get('/appointment/gettimeslot', 'Front\FrontendController@getTimeSlot')->name('getTimeSlot');
        Route::get('/appointment/checkThisSlot', 'Front\FrontendController@checkThisSlot')->name('checkThisSlot');
    });
    Route::group(['middleware' => ['routeAccess:Portfolio']], function () {
        Route::get('/portfolios', 'Front\FrontendController@userPortfolios')->name('front.user.portfolios');
        Route::get('/portfolio/{slug}/{id}', 'Front\FrontendController@userPortfolioDetail')->name('front.user.portfolio.detail');
    });
    Route::get('/vcard/{id}', 'Front\FrontendController@vcard')->name('front.user.vcard');
    Route::get('/vcard-import/{id}', 'Front\FrontendController@vcardImport')->name('front.user.vcardImport');
    Route::get('/cv/{id}', 'Front\FrontendController@cv')->name('front.user.cv');
    Route::get('/user/changelanguage', 'Front\FrontendController@changeUserLanguage')->name('changeUserLanguage');
});
