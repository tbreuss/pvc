<?php

declare(strict_types=1);

namespace Tebe\Pvc\View;

interface ViewExtension
{
    /**
     * @param View $view
     */
    public function register(View $view);
}
