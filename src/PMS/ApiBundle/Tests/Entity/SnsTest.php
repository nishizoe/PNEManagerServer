<?php

namespace PMS\ApiBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Sns;
use PMS\ApiBundle\Entity\Server;
use PMS\ApiBundle\Entity\Account;

class SnsTest extends WebTestCase
{
    private $sns_;

    function setUp()
    {
        parent::setUp();
        $this->sns_ = new Sns();
    }

    /**
     * @test
     */
    function setDomain()
    {
        $this->sns_->setDomain('domain.insert.test');

        $this->assertSame('domain.insert.test', $this->sns_->getDomain());
    }

    /**
     * @test
     */
    function setStatus()
    {
        $this->sns_->setStatus('running');

        $this->assertEquals('running', $this->sns_->getStatus());
    }

    /**
     * @test
     */
    function setEmail()
    {
        $this->sns_->setEmail('watanabe@tejimaya.com');

        $this->assertEquals('watanabe@tejimaya.com', $this->sns_->getEmail());
    }

    /**
     * @test
     */
    function setAccount()
    {
        $account = new Account();

        $this->sns_->setAccount($account);

        $this->assertSame($account, $this->sns_->getAccount());
    }

    /**
     * @test
     */
    function setServer()
    {
        $server = new Server();

        $this->sns_->setServer($server);

        $this->assertSame($server, $this->sns_->getServer());
    }

    /**
     * @test
     */
    function persist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $account = new Account();
        $account->setEmail('watanabe@tejimaya.com');
        $em->persist($account);
        $em->flush();

        $server = new Server();
        $server->setHost('entity.test.com');
        $em->persist($server);
        $em->flush();

        $status = 'running';

        $this->sns_->setDomain('sns.entity.test.com');
        $this->sns_->setEmail('watanabe@tejimaya.com');
        $this->sns_->setAccount($account);
        $this->sns_->setServer($server);
        $this->sns_->setStatus($status);

        $em->persist($this->sns_);
        $em->flush();

        $sns = $em->getRepository('PMSApiBundle:Sns')->find($this->sns_->getId());

        $this->assertSame($this->sns_, $sns);
        $this->assertSame($this->sns_->getAccount(), $account);
        $this->assertSame($this->sns_->getServer(), $server);
    }

    /**
     * @test
     */
    function persistWithRelation()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $server = new Server();
        $server->setHost('host.sns.entity.test.com');
        $em->persist($server);
        $em->flush();

        $account = new Account();
        $account->setEmail('watanabe+test@tejimaya.com');
        $em->persist($account);
        $em->flush();

        $status = 'running';

        $this->sns_->setDomain('sns.entity.test.com');
        $this->sns_->setEmail('watanabe+test@tejimaya.com');
        $this->sns_->setStatus($status);
        $this->sns_->setServer($server);
        $this->sns_->setAccount($account);

        $em->persist($this->sns_);
        $em->flush();


        $sns = $em->getRepository('PMSApiBundle:Sns')->find($this->sns_->getId());

        $this->assertSame($this->sns_, $sns);
        $this->assertSame('sns.entity.test.com', $sns->getDomain());
        $this->assertSame($server, $sns->getServer());
        $this->assertSame($account, $sns->getAccount());
    }

}
