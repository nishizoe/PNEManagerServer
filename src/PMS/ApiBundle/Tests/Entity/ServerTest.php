<?php

namespace PMS\ApiBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Server;
use PMS\ApiBundle\Entity\Sns;

class ServerTest extends WebTestCase
{

    private $server_;

    function setUp()
    {
        parent::setUp();
        $this->server_ = new Server();
    }

    /**
     * @test
     */
    function setHost()
    {
        $this->server_->setHost('servertest.set.host.com');
        $this->assertEquals('servertest.set.host.com', $this->server_->getHost());
    }

    /**
     * @test
     */
    function persist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $this->server_->setHost('servertest.set.snss.com');

        $em->persist($this->server_);
        $em->flush();

        $sns = new Sns();
        $sns->setDomain('watanabe.pne.jp');
        $sns->setEmail('watanabe@tejimaya.com');
        $sns->setStatus('accepted');
        $sns->setServer($this->server_);
        $em->persist($sns);
        $em->flush();

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $server = $em->getRepository('PMSApiBundle:Server')->find($this->server_->getId());

        $this->assertSame('servertest.set.snss.com', $server->getHost());
        $snss = $server->getSnss();
        $this->assertNotNull($snss[0]);
        $this->assertNotNull($snss[0]->getId());
        $this->assertSame($sns->getId(), $snss[0]->getId());
    }

}
