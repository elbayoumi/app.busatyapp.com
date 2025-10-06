<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $settings = null;
        try {
            // Check if the 'settings' table exists
            if (Schema::hasTable('settings')) {

                // Retrieve settings from the database or cache
                $settings = cache()->remember('settings', 60 * 60, function () {
                    return Settings::first();
                });


                // If settings exist and the SMTP email is valid
                if ($settings && filter_var($settings->smtp_from_address, FILTER_VALIDATE_EMAIL)) {

                    // Configure SMTP settings dynamically
                    Config::set('mail.mailers.smtp.host', (string) $settings->smtp_host);
                    Config::set('mail.mailers.smtp.port', (string) $settings->smtp_port);
                    Config::set('mail.mailers.smtp.encryption', (string) $settings->smtp_encryption);
                    Config::set('mail.mailers.smtp.username', (string) $settings->smtp_username);
                    Config::set('mail.mailers.smtp.password', (string) $settings->smtp_password);
                    Config::set('mail.mailers.smtp.from.address', (string) $settings->smtp_from_address);
                    Config::set('mail.mailers.smtp.from.name', (string) $settings->smtp_from_name);
                    Config::set('mail.from.address', (string) $settings->smtp_from_address);
                    Config::set('mail.from.name', (string) $settings->smtp_from_name);
                // dd($settings->smtp_from_name);

                } else {
                    // Log a warning if settings are missing or invalid
                    Log::warning('SMTP settings are missing or invalid in the settings table.');
                }
            } else {
                // Log a warning if the 'settings' table does not exist
                Log::warning('The settings table does not exist.');
            }
        } catch (\Exception $e) {
            // Log any errors that occur during the setup process
            Log::error('Failed to initialize SMTP settings: ' . $e->getMessage());
        }

        // Share the settings with all views
        View::composer('*', function ($view) use ($settings) {
            $view->with(compact(['settings']));
        });
    }
}
