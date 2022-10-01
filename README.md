# Zoom Lumen Api
This repository belongs to with an implementation of `Zoom api` where the lumen framework is used independently. Besides a initial package is used for general settings.

## Installation

You can install the package via composer:

```bash
composer require macsidigital/laravel-zoom
```

For versioning:-

- 1.0 - deprecated - was a quick build for a client project, not recommended you use this version.

- 2.0 - Laravel 5.5 - 5.8 - deprecated, no longer maintained

- 3.0 - Laravel 6.0 - Maintained, feel free to create pull requests.  This is open source which is a 2 way street.

- 4.0 - Laravel 7.0 - 8.0 - Maintained, feel free to create pull requests.  This is open source which is a 2 way street.

### Configuration file

Publish the configuration file

```bash
php artisan vendor:publish --provider="MacsiDigital\Zoom\Providers\ZoomServiceProvider"
```

This will create a zoom.php config file within your config directory:-

```php
return [
    'apiKey' => env('ZOOM_CLIENT_KEY'),
    'apiSecret' => env('ZOOM_CLIENT_SECRET'),
    'baseUrl' => 'https://api.zoom.us/v2/',
    'token_life' => 60 * 60 * 24 * 7, // In seconds, default 1 week
    'authentication_method' => 'jwt', // Only jwt compatible at present
    'max_api_calls_per_request' => '5' // how many times can we hit the api to return results for an all() request
];
```

You need to add ZOOM_CLIENT_KEY and ZOOM_CLIENT_SECRET into your .env file.

Also note the tokenLife, there were numerous users of the old API who said the token expired to quickly, so we have set for a longer lifeTime by default and more importantly made it customisable.

That should be it.

### Connecting

To get an access point you can simply create a new instance and the resource.

``` php
    $user = Zoom::user();
```

### Accessing models

There are 2 main ways to work with models, to call them directly from the access entry point via a facade, or to call them in the standard php 'new' method and pass in the access entry point

``` php
    $user = Zoom::user();
    //or
    
    $zoom = new \MacsiDigital\Zoom\Support\Entry;
    $user = new \MacsiDigital\Zoom\User($zoom);
```

### Custom settings
If you would like to use different configuration values than those in your zoom.php config file, you can feed those as parameters to \MacsiDigital\Zoom\Support\Entry as shown below.
``` php
    $zoom = new \MacsiDigital\Zoom\Support\Entry($apiKey, $apiSecret, $tokenLife, $maxQueries, $baseUrl);
```

### General Implementation Workflow

The zoom meetings are created by maintaining different zoom account of different organization. Therefore, a live meeting account table set up is created where the 
`api_key` and `secret_key` is added initially. After that, the account credientials are fetched and the implementation has been done in `controllers/WebinarEventController` mentioning the `store` , `update` and `delete`.

