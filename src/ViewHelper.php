<?php

declare(strict_types=1);

namespace Tebe\Pvc;

interface ViewHelper
{
    /**
     * @param array $args
     * @return mixed
     */
    public function execute(array $args = []);
}
