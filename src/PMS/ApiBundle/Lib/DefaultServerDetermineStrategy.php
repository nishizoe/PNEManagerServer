<?php

namespace PMS\ApiBundle\Lib;

use PMS\ApiBundle\Lib\ServerDetermineStrategy;

class DefaultServerDetermineStrategy implements ServerDetermineStrategy
{

    public function determine($doctrine)
    {
        $server = $doctrine->getRepository('PMSApiBundle:Server')->findOneBy(array());
        if (!$server)
        {
            throw new \RuntimeException('there are no server');
        }

        return $server->getId();
    }

}
