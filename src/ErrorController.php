<?php

namespace Tebe\Pvc;

use Throwable;

class ErrorController extends Controller
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
     * @return string
     * @throws Exception\SystemException
     */
    public function errorAction(): string
    {
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
