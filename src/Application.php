<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tebe\Pvc\Middleware\MiddlewareDispatcher;
use Tebe\Pvc\Middleware\RouterMiddleware;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;

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
     * @var Router
     */
    private $router;

    /**
     * @var View
     */
    private $view;

    /**
     * @var View
     */
    private $layout;

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
     * @param array $config
     */
    private function __construct(array $config)
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
        $this->setConfig(new Config($config));
        $this->setRequest($request);
        $this->setRouter(new Router($config['controllersPath']));
        $this->setView(new View($config['viewsPath']));
        $this->setLayout(new View($config['layoutsPath']));
        $this->setEventDispatcher(new EventDispatcher());
        $this->middlewares = [];
    }

    /**
     * @param array|null $config
     * @return Application
     * @throws Exception
     */
    public static function instance(array $config = null)
    {
        if (is_null(static::$instance)) {
            if (is_null($config)) {
                throw new Exception('Config array is empty');
            }
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    /**
     * @param Config $config
     */
    private function setConfig(Config $config)
    {
        $this->config = $config;
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
     */
    private function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Router $router
     */
    private function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param View $view
     */
    private function setView(View $view)
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
     * @param View $layout
     */
    private function setLayout(View $layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return View
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        $dispatcher = new Dispatcher(
            $this->getRequest(),
            $this->getLayout(),
            $this->getView()
        );
        return $dispatcher;
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
        $middlewares = array_merge(
            $this->middlewares,
            [new RouterMiddleware($this->getRouter(), $this->getDispatcher())]
        );

        $middlewareDispatcher = new MiddlewareDispatcher(
            $middlewares,
            function () {
                return new HtmlResponse('', 200);
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
