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

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $snss = $em->getRepository('PMSApiBundle:Sns')->findAll();
        foreach ($snss as $sns)
        {
          $em->remove($sns);
        }
        $servers = $em->getRepository('PMSApiBundle:Server')->findAll();
        foreach ($servers as $server)
        {
          $em->remove($server);
        }
        $accounts = $em->getRepository('PMSApiBundle:Account')->findAll();
        foreach ($accounts as $account)
        {
          $em->remove($account);
        }
        $em->flush();
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
    function setVersion()
    {
      $this->sns_->setVersion('hosting-3.8.0-d');

      $this->assertSame('hosting-3.8.0-d', $this->sns_->getVersion());
    }

    /**
     * @test
     */
    function persist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $account = new Account();
        $account->setEmail('watanabesnspersist@tejimaya.com');
        $em->persist($account);
        $em->flush();

        $server = new Server();
        $server->setHost('entity.test.com');
        $em->persist($server);
        $em->flush();

        $this->sns_->setDomain('sns.entity.test.com');
        $this->sns_->setEmail('watanabesnspersist@tejimaya.com');
        $this->sns_->setStatus('running');
        $this->sns_->setAccount($account);
        $this->sns_->setServer($server);
        $this->sns_->setVersion('hosting-3.8.0-b');

        $em->persist($this->sns_);
        $em->flush();

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $sns = $em->getRepository('PMSApiBundle:Sns')->find($this->sns_->getId());

        $this->assertAttributeSame('sns.entity.test.com', 'domain', $sns);
        $this->assertAttributeSame('watanabesnspersist@tejimaya.com', 'email', $sns);
        $this->assertAttributeSame('running', 'status', $sns);
        $this->assertSame($account->getId(), $sns->getAccount()->getId());
        $this->assertSame($server->getId(), $sns->getServer()->getId());
        $this->assertAttributeSame('hosting-3.8.0-b', 'version', $sns);
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

        $this->sns_->setDomain('sns.entity.test.com');
        $this->sns_->setEmail('watanabe+test@tejimaya.com');
        $this->sns_->setStatus('running');
        $this->sns_->setServer($server);
        $this->sns_->setAccount($account);
        $this->sns_->setVersion('hosting-3.8.0-c');

        $em->persist($this->sns_);
        $em->flush();

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $sns = $em->getRepository('PMSApiBundle:Sns')->find($this->sns_->getId());

        $this->assertAttributeSame('sns.entity.test.com', 'domain', $sns);
        $this->assertAttributeSame('watanabe+test@tejimaya.com', 'email', $sns);
        $this->assertAttributeSame('running', 'status', $sns);
        $this->assertNotNull($sns->getAccount()->getId());
        $this->assertSame($account->getId(), $sns->getAccount()->getId());
        $this->assertNotNull($sns->getServer()->getId());
        $this->assertSame($server->getId(), $sns->getServer()->getId());
        $this->assertAttributeSame('hosting-3.8.0-c', 'version', $sns);
    }

}
