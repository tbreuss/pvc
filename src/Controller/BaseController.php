<?php

declare(strict_types=1);

namespace Tebe\Pvc\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\View\View;

abstract class BaseController
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
    private $controllerName;

    /**
     * @var string
     */
    private $actionName;

    /**
     * Controller constructor.
     * @param View $view
     * @param ServerRequestInterface $request
     * @param string $pathInfo
     */
    public function __construct(View $view, ServerRequestInterface $request, string $pathInfo)
    {
        // TODO think of why not getting $pathInfo from $request?
        [$controllerName, $actionName] = explode('/', $pathInfo);
        $this->setView($view);
        $this->setRequest($request);
        $this->setControllerName($controllerName);
        $this->setActionName($actionName);
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
     * @param string $viewName
     * @param array $params
     * @return string
     * @throws SystemException
     */
    protected function render(string $viewName, array $params = []): string
    {
        $viewRoute = $this->resolveViewPath($viewName);
        $content = $this->renderPartial($viewRoute, $params);
        $html = $this->renderPartial('layouts/default', ['content' => $content]);
        return $html;
    }

    /**
     * @param string $viewName
     * @param array $params
     * @return string
     * @throws SystemException
     */
    protected function renderPartial(string $viewName, array $params = []): string
    {
        $viewRoute = $this->resolveViewPath($viewName);
        return $this->view->render($viewRoute, $params);
    }

    /**
     * @param string $viewName
     * @return string
     */
    private function resolveViewPath(string $viewName): string
    {
        $viewRoute = $viewName;
        if (strpos($viewName, '/') === false) {
            $viewRoute = sprintf('%s/%s', $this->controllerName, $viewName);
        }
        return $viewRoute;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @param string $controllerName
     */
    private function setControllerName(string $controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     */
    private function setActionName(string $actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return string
     */
    public function getActionMethod(): string
    {
        return $this->actionName . 'Action';
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->controllerName . '/' . $this->actionName;
    }

    /**
     * @return array
     */
    public function httpMethods()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAllowedHttpMethods()
    {
        $actionName = $this->getActionName();
        $httpMethods = $this->httpMethods();
        if (empty($httpMethods[$actionName])) {
            return ['GET'];
        }
        if (!empty($httpMethods[$actionName])) {
            return array_map('strtoupper', $httpMethods[$actionName]);
        }
        return [];
    }
}
