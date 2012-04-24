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
    function setSnss()
    {
        $sns = new Sns();

        $this->server_->getSnss()->add($sns);

        $this->assertSame($sns, $this->server_->getSnss()->get(0));
    }

    /**
     * @test
     */
    function persist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $sns = new Sns();
        $sns->setDomain('watanabe.pne.jp');
        $sns->setEmail('watanabe@tejimaya.com');
        $sns->setStatus('accepted');
        $em->persist($sns);
        $em->flush();

        $this->server_->setHost('servertest.set.snss.com');
        $this->server_->getSnss()->add($sns);

        $em->persist($this->server_);
        $em->flush();

        $server = $em->getRepository('PMSApiBundle:Server')->find($this->server_->getId());

        $this->assertSame($this->server_, $server);
        $this->assertSame($this->server_->getSnss()->get(0), $sns);
    }

}
