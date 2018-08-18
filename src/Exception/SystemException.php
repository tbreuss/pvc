<?php

declare(strict_types=1);

namespace Tebe\Pvc\Exception;

use Exception;
use Throwable;

class SystemException extends Exception
{

    /**
     * @param string $class
     * @param string $format
     * @return static
     */
    public static function classNotExist(string $class, string $format = 'Class "%s" does not exist')
    {
        $message = sprintf($format, $class);
        return new static($message, 500);
    }

    /**
     * @param string $method
     * @param string $format
     * @return static
     */
    public static function methodNotExist(string $method, string $format = 'Method "%s" does not exist')
    {
        $message = sprintf($format, $method);
        return new static($message, 500);
    }

    /**
     * @param string $filepath
     * @param string $format
     * @return static
     */
    public static function includeFileNotExist(string $filepath, string $format = 'Include file "%s" does not exist')
    {
        $message = sprintf($format, $filepath);
        return new static($message, 500);
    }

    /**
     * @param string $directory
     * @param string $format
     * @return static
     */
    public static function directoryNotExist(string $directory, string $format = 'Directory "%s" does not exist')
    {
        $message = sprintf($format, $directory);
        return new static($message, 500);
    }


    /**
     * @param string $file
     * @param string $format
     * @return static
     */
    public static function fileNotExist(string $file, string $format = 'File "%s" does not exist')
    {
        $message = sprintf($format, $file);
        return new static($message, 500);
    }

    /**
     * @param string $message
     * @param Throwable $t
     * @return static
     */
    public static function serverError(string $message, Throwable $t = null)
    {
        return new static($message, 500, $t);
    }

}
