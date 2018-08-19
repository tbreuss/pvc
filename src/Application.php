<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tebe\HttpFactory\HttpFactory;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\Middleware\MiddlewareDispatcher;
use Tebe\Pvc\Middleware\RouterMiddleware;

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
     * @throws SystemException
     */
    public static function instance()
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
    public function getConfig()
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
    public function getRequest()
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
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     * @return $this
     */
    public function setMiddleware(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->addMiddleware($middleware);
        }
        return $this;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher() : EventDispatcher
    {
        return $this->eventDispatcher;
    }

    /**
     * @param string $eventName
     * @param EventHandler $eventHandler
     * @return $this
     */
    public function addEventHandler(string $eventName, EventHandler $eventHandler)
    {
        $this->eventDispatcher->addHandler($eventName, $eventHandler);
        return $this;
    }

    /**
     * Run
     */
    public function run(): void
    {
        $viewsPath = $this->config->get('viewsPath');
        $view = new View($viewsPath);
        $this->setView($view);

        $middlewares = array_merge(
            $this->middlewares,
            [new RouterMiddleware($this->getView(), $this->getConfig()->get('controllersPath'))]
        );

        $middlewareDispatcher = new MiddlewareDispatcher(
            $middlewares,
            function () {
                return (new HttpFactory)->createResponse(200);
            }
        );

        $request = $this->getRequest();
        $response = $middlewareDispatcher->handle($request);

        $this->emit($response);
    }

    /**
     * @param ResponseInterface $response
     */
    private function emit(ResponseInterface $response)
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
}
