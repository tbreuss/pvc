<?php

namespace example\components;

use Tebe\Pvc\Application;
use Tebe\Pvc\Event\Event;
use Tebe\Pvc\Event\EventHandler;
use Tebe\Pvc\Exception\SystemException;

class AuthLoggingEventHandler implements EventHandler
{

    protected $logFile;

    /**
     * AuthLoggingEventHandler constructor.
     * @param string $logFile
     */
    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
    }

    /**
     * @param Event $event
     * @throws SystemException
     */
    public function handle(Event $event) : void
    {

        $authData = $event->getInfo();

        $fields = [
                    date('Y-m-d H:i:s'),
                    json_encode(Application::instance()->getRequest()->getServerParams()),
                    $event->getName(),
                    $authData['user'],
                    $authData['password']
                ];

        error_log(implode('|', $fields) . "\n", 3, $this->logFile);
    }
}
