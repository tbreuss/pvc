<?php

declare(strict_types=1);

namespace Tebe\Pvc;

class Controller
{
    /**
     * @var View
     */
    private $view;

    /**
     * @var Route
     */
    private $route;

    /**
     * Controller constructor.
     * @param View $view
     * @param Route $route
     */
    public function __construct(View $view, Route $route)
    {
        $this->setView($view);
        $this->setRoute($route);
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
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    private function setRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param string $viewName
     * @param array $params
     * @return string
     * @throws \Exception
     */
    protected function render(string $viewName, array $params = []): string
    {
        $viewRoute = $this->resolveViewPath($viewName);
        try {
            $content = $this->renderPartial($viewRoute, $params);
            $html = $this->renderPartial('layouts/default', ['content' => $content]);
            return $html;
        } catch (\Throwable $t) {
            ob_clean();
            throw new \Exception($t->getMessage(), 0, $t);
        }
    }

    /**
     * @param string $viewName
     * @param array $params
     * @return string
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
            $controllerName = $this->route->getControllerName();
            $viewRoute = sprintf('%s/%s', $controllerName, $viewName);
        }
        return $viewRoute;
    }

}
