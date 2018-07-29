<?php

declare(strict_types=1);

namespace Tebe\Pvc\ViewHelper;

use Tebe\Pvc\ViewHelper;

class EscapeViewHelper implements ViewHelper
{
    public function execute(array $args = [])
    {
        return htmlspecialchars($args[0]);
    }
}
