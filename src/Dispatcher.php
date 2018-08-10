<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Dispatcher
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var View
     */
    private $view;

    /**
     * Dispatcher constructor.
     * @param ServerRequestInterface $request
     * @param View $view
     */
    public function __construct(ServerRequestInterface $request, View $view)
    {
        $this->setRequest($request);
        $this->setView($view);
    }

    /**
     * @param Route $route
     * @return string
     * @throws \Exception
     */
    public function dispatch(Route $route): string
    {
        $controllerClassName = $route->getControllerClassName();
        $actionMethod = $route->getActionMethod();

        $controller = new $controllerClassName($this->view, $route);

        $queryParams = $this->getHttpGetVars($controller, $actionMethod);
        $html = call_user_func_array([$controller, $actionMethod], $queryParams);

        return $html;
    }

    /**
     * Get the HTTP GET vars which are requested by the method using method parameters (a kind of injecting the get
     * vars into the action method name).
     * @param Controller $controller
     * @param string $methodName
     * @return array
     */
    private function getHttpGetVars(Controller $controller, string $methodName): array
    {
        $requestParams = [];
        $reflectionMethod = new \ReflectionMethod($controller, $methodName);
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            $name = $reflectionParameter->getName();
            $queryParams = $this->request->getQueryParams();
            if (isset($queryParams[$name])) {
                $requestParams[$name] = $queryParams[$name];
            }
        }

        return $requestParams;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     */
    private function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * @param View $view
     */
    private function setView(View $view)
    {
        $this->view = $view;
    }
}
