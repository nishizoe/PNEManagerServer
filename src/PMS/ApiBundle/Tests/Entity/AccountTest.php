<?php

namespace PMS\ApiBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Account;

class AccountTest extends WebTestCase
{
    private $account_;

    function setUp()
    {
        parent::setUp();
        $this->account_ = new Account();
    }

    /**
     * @test
     */
    function setEmail()
    {
        $this->account_->setEmail('watanabe@tejimaya.com');

        $this->assertSame('watanabe@tejimaya.com', $this->account_->getEmail());
    }

    /**
     * @test
     */
    function persist()
    {
        $this->account_->setEmail('watanabe@tejimaya.com');

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $em->persist($this->account_);
        $em->flush();

        $account = $em->getRepository('PMSApiBundle:Account')->find($this->account_->getId());

        $this->assertSame($this->account_, $account);
    }

}
