<?php

declare(strict_types=1);

namespace Tebe\Pvc\ViewHelper;

use Tebe\Pvc\Helper\Url;
use Tebe\Pvc\ViewHelper;

class UrlViewHelper implements ViewHelper
{
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            return '';
        }
        return Url::to($args[0]);
    }
}
