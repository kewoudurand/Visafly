<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AffiliateWithdrawal;
use App\Models\Referral;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Observers\WithdrawalObserver;
use App\Observers\ReferralObserver;
use App\Observers\UserObserver;
use App\Observers\CourseObserver;
use App\Observers\LessonObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Enregistrer les observers
        AffiliateWithdrawal::observe(WithdrawalObserver::class);
        Referral::observe(ReferralObserver::class);
        User::observe(UserObserver::class);
        Course::observe(CourseObserver::class);
        Lesson::observe(LessonObserver::class);
    }

    public function register()
    {
        //
    }
}
