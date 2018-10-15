<?php

declare(strict_types=1);

namespace Tebe\Pvc\Helper;

use Psr\Http\Message\ServerRequestInterface;

class RequestHelper
{
    public static function getPathInfo(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();
        $pathInfo = $serverParams['PATH_INFO'] ?? '';
        $pathInfo = trim($pathInfo, '/');
        if (empty($pathInfo)) {
            $pathInfo = 'index/index';
        }
        if (strpos($pathInfo, '/') === false) {
            $pathInfo .= '/index';
        }
        return $pathInfo;
    }
}
