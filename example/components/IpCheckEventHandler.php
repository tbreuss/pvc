<?php

namespace example\components;

use Tebe\Pvc\Application;
use Tebe\Pvc\Event\Event;
use Tebe\Pvc\EventHandler;

class IpCheckEventHandler implements EventHandler
{

    protected $blockedIps;

    public function __construct($blockedIps)
    {
        $this->blockedIps = $blockedIps;
    }

    public function handle(Event $event) : void
    {
        $request  = Application::instance()->getRequest();
        $ipAdress = $request->getRemoteAddress();

        if (in_array($ipAdress, $this->blockedIps)) {
            $event->cancel();
        }
    }
}
