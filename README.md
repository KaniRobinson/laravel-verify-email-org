# Laravel Verify Email Email Validation

Laravel Email Verification that connects to the [Verify Email Service](https://verify-email.org).

## Installation

```
composer require kanirobinson/laravel-verify-email-org
```

Add the package to list of service providers in `config/app.php`

```
<?php
    ...
    'providers' => [
        ...
        KaniRobinson\LaravelVerifyEmailOrg\ServiceProvider::class,
    ],

```

Publish and fill out the config/verify-email-config.php file with your Verify Email API key.

```
php artisan vendor:publish --provider="KaniRobinson\LaravelVerifyEmailOrg\ServiceProvider"
```
## Usage

Use the rule verify_email in your validation like so:

```
/**
 * Get a validator for an incoming registration request.
 *
 * @param  array  $data
 * @return \Illuminate\Contracts\Validation\Validator
 */
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => 'required|max:255',
        'email' => 'required|email|verify_email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);
}
```