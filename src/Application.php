<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tebe\Pvc\Middleware\MiddlewarePipe;
use Tebe\Pvc\Middleware\RequestHandler;

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
        $this->middlewares = [];
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
     * @return $this
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
     * @return $this
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
     */
    public function setView(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return View
     * @throws Exception\SystemException
     */
    public function getView(): View
    {
        if (is_null($this->view)) {
            $viewsPath = $this->config->get('viewsPath');
            $view = new View($viewsPath);
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
     * @return $this
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
     */
    private function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
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
     * Run
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
}
