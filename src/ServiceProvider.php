<?php

namespace KaniRobinson\LaravelVerifyEmailOrg;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Validator;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Publish Config File
     *
     * @return void
     */
    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../config/verify-email-config.php' => config_path('verify-email-config.php')
        ], 'config');
    }

    /**
     * Load Translations
     *
     * @return void
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang/', 'laravel-verify-email-org');
    }

    /**
     * Create Validation Rule
     *
     * @return void
     */
    protected function createValidationRule()
    {
        $validationMessage = $this->app
            ->translator
            ->trans('laravel-verify-email-org::validation.verify_email');

        Validator::extend('verify_email', 'KaniRobinson\LaravelVerifyEmailOrg\EmailRule@validate', $validationMessage);
    }

    /**
     * Boot Method.
     *
     */
    public function boot()
    {
        $this->publish();
        $this->loadTranslations();
        $this->createValidationRule();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmailRule::class, function ($app) {
            return new EmailRule(
                new Client(), 
                $app['log'], 
                config('verify-email-config.key')
            );
        });
    }
}
