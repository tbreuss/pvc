<?php

namespace example\components;

use Tebe\Pvc\View\ViewExtension;
use Tebe\Pvc\View\View;

class MyViewExtension implements ViewExtension
{
    public function register(View $view)
    {
        $view->registerHelper('hello', function (string $name = '') {
            return sprintf("Hallo %s", $name);
        });
        $view->registerHelper('add', function (float ...$operands) {
            return array_sum($operands);
        });
    }
}
