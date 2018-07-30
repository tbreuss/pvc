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
    private $layout;

    /**
     * @var View
     */
    private $view;

    /**
     * Dispatcher constructor.
     * @param ServerRequestInterface $request
     * @param View $layout
     * @param View $view
     */
    public function __construct(ServerRequestInterface $request, View $layout, View $view)
    {
        $this->setRequest($request);
        $this->setLayout($layout);
        $this->setView($view);
    }

    /**
     * @param Route $route
     * @return ResponseInterface
     * @throws \Exception
     */
    public function dispatch(Route $route): ResponseInterface
    {
        $controllerClassName = $route->getControllerClassName();
        $actionMethod = $route->getActionMethod();

        $controller = new $controllerClassName($this->view, $route);

        $queryParams = $this->getHttpGetVars($controller, $actionMethod);
        $content = call_user_func_array([$controller, $actionMethod], $queryParams);

        $html = $this->layout->render('default', [
            'content' => $content
        ]);

        $response = new HtmlResponse($html);
        return $response;
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
    public function getLayout(): View
    {
        return $this->layout;
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
