<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();

$app->register(Jenssegers\Mongodb\MongodbServiceProvider::class);
//$app->register('Moloquent\MongodbServiceProvider');
$app->withEloquent();



/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register and bind filesystem
|--------------------------------------------------------------------------
*/

$app->singleton('filesystem', function ($app) {
    return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
});


/*
|--------------------------------------------------------------------------
| Register configuration settings
|--------------------------------------------------------------------------
*/
$app->configure('filesystems');
$app->configure('amadeus');
$app->configure('app');
$app->configure('cache');
$app->configure('logging');
$app->configure('sentry');
$app->configure('services');
$app->configure('mail');

/*
|--------------------------------------------------------------------------
| Register monolog
|--------------------------------------------------------------------------
*/
/*$app->configureMonologUsing(function (\Monolog\Logger $monolog) {
    $handler = new \Monolog\Handler\StreamHandler(storage_path('logs/lumen.log'));
    $handler->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, true, true));
    $monolog->pushHandler($handler);
    return $monolog;
});
 */
/*
|--------------------------------------------------------------------------
| Register flight service
|--------------------------------------------------------------------------
*/

$app->bind(
    \App\Service\Flight\Contract\BookAirService::class, function () {
    return new \App\Service\Flight\BookAirService(new \App\Service\Client\AmadeusClient());
});

$app->singleton(
    \App\Service\Flight\Contract\SearchAirService::class, function () {
    return new \App\Service\Flight\SearchAirService(new \App\Service\Client\AmadeusClient());
});
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->routeMiddleware([
    'api.key' => App\Http\Middleware\ApiKeyMiddleware::class,
    'auth'=> App\Http\Middleware\Authenticate::class,
]);
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register('Nathanmac\Utilities\Parser\ParserServiceProvider');
$app->register('Robbo\Presenter\PresenterServiceProvider');
$app->register('Illuminate\Redis\RedisServiceProvider');
$app->register('Sentry\SentryLaravel\SentryLumenServiceProvider');
$app->register(App\Providers\AuthServiceProvider::class);

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

//Facade
//class_alias('Nathanmac\Utilities\Parser\Facades\Parser', 'Parser');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
