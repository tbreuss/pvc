<?php

namespace Tebe\Pvc;

class ErrorController extends Controller
{
    /**
     * @var \Throwable
     */
    private $error;

    public function setError($error)
    {
        $this->error = $error;
    }

    public function errorAction()
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
    private function getViewName(int $status)
    {
        $viewName = 'error/' . $status;
        if ($this->getView()->fileExist($viewName)) {
            return $viewName;
        };
        $viewName = 'error/error';
        if ($this->getView()->fileExist($viewName)) {
            return $viewName;
        };
        return '';
    }
}
