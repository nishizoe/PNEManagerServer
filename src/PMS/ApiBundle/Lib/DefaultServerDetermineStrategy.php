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
            $message = "OpnePNEのデプロイで使用するサーバーデータが登録されていません\n";
            $message .= "以下のコマンドを実行して、サーバーデータを登録してください\n";
            $message .= "curl \"http://{$_SERVER['SERVER_NAME']}/api/server/add\" -d \"host=[ServerDomain]\"\n";
            $message .= "[ServerDomain]にはOpenPNEをデプロイするドメイン名を指定してください(pne.jpなど)";

            throw new \RuntimeException($message);
        }

        return $server->getId();
    }

}
