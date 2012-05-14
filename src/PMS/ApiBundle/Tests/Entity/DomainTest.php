<?php

namespace PMS\ApiBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Domain;

class DomainTest extends WebTestCase
{

    private $domain_;

    function setUp()
    {
        parent::setUp();
        $this->domain_ = new Domain();
    }

    /**
     * @test
     */
    function setDomain()
    {
        $this->domain_->setDomain('sns.mydomain.com');
        $this->assertEquals('sns.mydomain.com', $this->domain_->getDomain());
    }

    /**
     * @test
     */
    function setType()
    {
        $this->domain_->setType('sns');
        $this->assertEquals('sns', $this->domain_->getType());
    }

    /**
     * @test
     */
    function persist()
    {
        $this->domain_->setDomain('sns.example.com');
        $this->domain_->setType('sns');

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $em->persist($this->domain_);
        $em->flush();

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $domain = $em->getRepository('PMSApiBundle:Domain')->find($this->domain_->getId());

        $this->assertSame('sns.example.com', $domain->getDomain());
        $this->assertSame('sns', $domain->getType());
    }

}
