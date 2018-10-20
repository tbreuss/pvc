<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tebe\Pvc\Event\EventDispatcher;
use Tebe\Pvc\Event\EventHandler;
use Tebe\Pvc\Helper\UrlHelper;
use Tebe\Pvc\Middleware\MiddlewarePipe;
use Tebe\Pvc\Middleware\RequestHandler;
use Tebe\Pvc\View\ViewExtension;
use Tebe\Pvc\View\ViewHelpers;
use Tebe\Pvc\View\View;

class Application
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var View
     */
    private $view;

    /**
     * @var array
     */
    private $viewExtensions;

    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $this->setEventDispatcher(new EventDispatcher());
        $this->setMiddlewares([]);
    }

    /**
     * @return Application
     */
    public static function instance(): Application
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param array $config
     * @return Application
     */
    public function setConfig(array $config)
    {
        $this->config = new Config($config);
        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Application
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param View $view
     * @return Application
     */
    public function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return View
     * @throws Exception\SystemException
     */
    public function getView(): View
    {
        if (is_null($this->view)) {
            $viewsPath = $this->config->get('viewsPath');
            $helpers = new ViewHelpers();
            $view = new View($viewsPath, $helpers);

            // TODO move to own ViewExtension class
            $view->registerHelper('escape', function (string $string) {
                return htmlspecialchars($string);
            });
            $view->registerHelper('url', function ($args) {
                return UrlHelper::to($args);
            });

            foreach ($this->viewExtensions as $viewExtension) {
                $view->registerExtension($viewExtension);
            }

            $this->setView($view);
        }
        return $this->view;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware): Application
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     * @return Application
     */
    public function setMiddlewares(array $middlewares): Application
    {
        foreach ($middlewares as $middleware) {
            $this->addMiddleware($middleware);
        }
        return $this;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     * @return Application
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher(): EventDispatcher
    {
        return $this->eventDispatcher;
    }

    /**
     * @param string $eventName
     * @param EventHandler $eventHandler
     * @return $this
     */
    public function addEventHandler(string $eventName, EventHandler $eventHandler): Application
    {
        $this->eventDispatcher->addHandler($eventName, $eventHandler);
        return $this;
    }

    /**
     * @throws Exception\SystemException
     */
    public function run(): void
    {
        $request = $this->getRequest();
        $requestHandler = $this->getRequestHandler();
        $middlewarePipe = $this->getMiddlewarePipe();
        $response = $middlewarePipe->process($request, $requestHandler);
        $this->emit($response);
    }

    /**
     * @param ResponseInterface $response
     */
    private function emit(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        http_response_code($statusCode);

        foreach ($response->getHeaders() as $k => $values) {
            foreach ($values as $v) {
                header(sprintf('%s: %s', $k, $v), false);
            }
        }

        echo $response->getBody();
    }

    /**
     * @return RequestHandler
     * @throws Exception\SystemException
     */
    private function getRequestHandler(): RequestHandler
    {
        $view = $this->getView();
        $controllersPath = $this->getConfig()->get('controllersPath');
        $requestHandler = new RequestHandler($view, $controllersPath);
        return $requestHandler;
    }

    /**
     * @return MiddlewarePipe
     */
    private function getMiddlewarePipe(): MiddlewarePipe
    {
        $middlewarePipe = MiddlewarePipe::create($this->middlewares);
        return $middlewarePipe;
    }

    /**
     * @param ViewExtension[] $viewExtensions
     * @return Application
     */
    public function setViewExtensions(array $viewExtensions)
    {
        $this->viewExtensions = $viewExtensions;
        return $this;
    }
}
