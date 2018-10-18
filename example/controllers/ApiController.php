<?php

namespace example\controllers;

use Tebe\Pvc\Controller\BaseController;

class ApiController extends BaseController
{
    public function indexAction()
    {
        return [
            'a' => 123,
            'b' => 456,
            'c' => 789
        ];
    }
}
