<?php

namespace PMS\ApiBundle\Controller\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{

  public function onKernelRequest(GetResponseEvent $event)
  {
      $server = $event->getRequest()->server;
      $uri = parse_url($server->get('REQUEST_URI'));
      if (!in_array($uri['path'], array('/api/domain/available', '/api/sns/apply')) && !in_array($server->get('REMOTE_ADDR'), array('203.143.101.162')))
      {
          exit;
      }
  }

}
