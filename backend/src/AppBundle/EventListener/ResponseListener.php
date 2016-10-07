<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $header = $event->getResponse()->headers;
        $header->set('Access-Control-Allow-Origin', '*');
        $header->set('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, Content-Type, accept, pagination, x-xsrf-token');
        $header->set('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
    }
}