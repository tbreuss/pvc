<?php

declare(strict_types=1);

namespace Tebe\Pvc\Controller;

use Tebe\Pvc\Application;
use Tebe\Pvc\Exception\SystemException;
use Throwable;

class ErrorController extends BaseController
{
    /**
     * @var Throwable
     */
    private $error;

    /**
     * @param Throwable $error
     */
    public function setError(Throwable $error)
    {
        $this->error = $error;
    }

    /**
     * @return Throwable
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     * @throws SystemException
     */
    public function errorAction()
    {
        $request = Application::instance()->getRequest();
        $acceptHeaders = $request->getHeaderLine('Accept');

        // json output
        if (strpos($acceptHeaders, 'application/json') !== false) {
            return [
                'code' => $this->error->getCode(),
                'file' => $this->error->getFile(),
                'line' => $this->error->getLine(),
                'message' => $this->error->getMessage(),
                'trace' => $this->error->getTraceAsString(),
            ];
        }

        // user view file
        $viewName = $this->getViewName($this->error->getCode());
        if (!empty($viewName)) {
            return $this->render($viewName, [
                'error' => $this->error
            ]);
        }
        return
            $this->error->getMessage()
            . '<br>'
            . $this->error->getTraceAsString();
    }

    /**
     * @param int $status
     * @return string
     */
    private function getViewName(int $status): string
    {
        $viewNames = ['error/' . $status, 'error/error'];
        foreach ($viewNames as $viewName) {
            if ($this->getView()->fileExist($viewName)) {
                return $viewName;
            }
        }
        return '';
    }
}
