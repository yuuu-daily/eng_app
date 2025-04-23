<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\AwsService;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });


       Fortify::authenticateUsing(function (Request $request) {
           $user = User::where('email', $request->email)->first();
           if ($user && Hash::check($request->password, $user->password)) {
               $user->update(['last_login_at' => now()]);
            //    if ($user->profile_photo_path) {
                // // $juku = Juku::find($user->juku_id);
                // $parsed = parse_url($user->profile_photo_path, PHP_URL_PATH);
                // $s3key = ltrim($parsed, '/');
                // $bucket = 'yuuu-cdn';
                // $photo_url = AwsService::getSignedUrlFromKey($s3key, $bucket);
                // session(['profile_url' => $photo_url]);
                // return $user;
            // }
               return $user;
           }
       });
    }
}
