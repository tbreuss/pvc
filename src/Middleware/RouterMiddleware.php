<?php

declare(strict_types=1);

namespace Tebe\Pvc\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tebe\Pvc\Controller;
use Tebe\Pvc\ErrorController;
use Tebe\Pvc\Exception\HttpException;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\View;

class RouterMiddleware implements MiddlewareInterface
{
    /**
     * @var View
     */
    private $view;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $controllersPath;

    /**
     * RouterMiddleware constructor.
     * @param View $view
     * @param string $controllersPath
     */
    public function __construct(View $view, string $controllersPath)
    {
        $this->setView($view);
        $this->setControllersPath($controllersPath);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->request = $request;
        $response = $handler->handle($request);
        $pathInfo = $this->getPathInfo();

        try {

            $controller = $this->resolveController($pathInfo);
            $html = $this->executeAction($controller);

        } catch (\Throwable $t) {

            // handle errors with built-in error controller
            $controller = new ErrorController($this->view, 'error/error');
            $controller->setError($t);
            $html = $this->executeAction($controller);
            $response = $response->withStatus($t->getCode());

        }

        $response->getBody()->write($html);
        return $response;
    }

    /**
     * @param string $pathInfo
     * @return Controller
     * @throws HttpException
     */
    private function resolveController(string $pathInfo): Controller
    {
        list($controllerName) = explode('/', $pathInfo);

        $controllerPath = sprintf('%s/%sController.php', $this->controllersPath, ucfirst($controllerName));

        if (!is_file($controllerPath)) {
            throw HttpException::notFound($pathInfo);
        }

        require_once($controllerPath);

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controller = new $controllerClass($this->view, $pathInfo);
        return $controller;
    }

    /**
     * @param Controller $controller
     * @return string
     * @throws HttpException
     * @throws SystemException
     */
    private function executeAction(Controller $controller): string
    {
        $actionMethod = $controller->getActionMethod();

        if (!method_exists($controller, $actionMethod)) {
            // throw not found error (404)
            throw HttpException::notFound($controller->getRoute());
        }

        try {
            $queryParams = $this->getHttpGetVars($controller, $actionMethod);
            $html = call_user_func_array([$controller, $actionMethod], $queryParams);
        } catch (\Throwable $t) {
            // catch all errors that happen within action handler
            throw SystemException::serverError($t->getMessage(), $t);
        }

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
     * @return string
     */
    private function getPathInfo(): string
    {
        $serverParams = $this->request->getServerParams();
        $pathInfo = $serverParams['PATH_INFO'] ?? '';
        $pathInfo = trim($pathInfo, '/');
        if (empty($pathInfo)) {
            $pathInfo = 'index/index';
        }
        if (strpos($pathInfo, '/') === false) {
            $pathInfo .= '/index';
        }
        return $pathInfo;
    }

    /**
     * @param View $view
     */
    private function setView(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param string $controllersPath
     * @throws SystemException
     */
    private function setControllersPath(string $controllersPath)
    {
        if (!is_dir($controllersPath)) {
            throw SystemException::directoryNotExist($controllersPath);
        }
        $this->controllersPath = $controllersPath;
    }

}
