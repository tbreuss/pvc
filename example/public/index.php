<?php

$loader = require __DIR__ . '/../../vendor/autoload.php';
// adding namespace, usually done in composer.json
$loader->setPsr4("example\\", __DIR__ . "/..");

use example\library\HttpBasicAuthMiddleware;
use example\library\HeaderMiddleware;
use example\library\HtmlMiddleware;
use example\library\ResponseTimeMiddleware;
use example\library\AuthLoggingEventHandler;
use example\library\MyViewExtension;
use Tebe\Pvc\Application;
use Zend\Diactoros\ServerRequestFactory;

try {
    $config = require '../config/main.php';
    $request = ServerRequestFactory::fromGlobals();

    Application::instance()
        ->setConfig($config)
        ->setRequest($request)
        ->addEventHandler('onRun', new AuthLoggingEventHandler(__DIR__ . '/auth.log'))
        ->setMiddlewares([
            new HtmlMiddleware('<!-- HTML Before 1 -->', HtmlMiddleware::MODE_PREPEND),
            new HtmlMiddleware('<!-- HTML After 3 -->', HtmlMiddleware::MODE_APPEND),
            new HtmlMiddleware('<!-- HTML After 2 -->', HtmlMiddleware::MODE_APPEND),
            new HtmlMiddleware('<!-- HTML Before 2 -->', HtmlMiddleware::MODE_PREPEND),
            new HtmlMiddleware('<!-- HTML After 1 -->', HtmlMiddleware::MODE_APPEND),
            new HtmlMiddleware('<!-- HTML Before 3 -->', HtmlMiddleware::MODE_PREPEND),
            new HeaderMiddleware('X-Pvc', '@dev'),
            new ResponseTimeMiddleware(),
            new HttpBasicAuthMiddleware(['user' => 'pass'])
        ])
        ->setViewExtensions([
            new MyViewExtension()
        ])
        ->run();
} catch (Throwable $t) {
    echo $t->getMessage() . '<br>';
    echo nl2br($t->getTraceAsString());
}
