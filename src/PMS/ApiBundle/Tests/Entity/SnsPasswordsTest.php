<?php

namespace PMS\ApiBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\SnsPasswords;
use PMS\ApiBundle\Entity\Sns;

class SnsPasswordsTest extends WebTestCase
{
    private $snsPasswords_;

    function setUp()
    {
        parent::setUp();
        $this->snsPasswords_ = new SnsPasswords();
    }
    
    /**
     * @test
     */
    function setMemberPassword()
    {
        $this->snsPasswords_->setMemberPassword('mpassword');
        $this->assertEquals('mpassword', $this->snsPasswords_->getMemberPassword());
    }
    
    /**
     * @test
     */
    function setAdminPassword()
    {
        $this->snsPasswords_->setAdminPassword('apassword');
        $this->assertEquals('apassword', $this->snsPasswords_->getAdminPassword());
    }
    
    /**
     * @test
     */
    function setSnsId()
    {
        $this->snsPasswords_->setSnsId(5241);
        $this->assertSame(5241, $this->snsPasswords_->getSnsId());
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
        $sns->setEmail('watanabepasswordpersist@tejimaya.com');
        $sns->setStatus('accepted');
        $sns->setVersion('hosting-3.8.0-c-test');
        $em->persist($sns);
        $em->flush();

        $this->snsPasswords_->setMemberPassword('mpassword');
        $this->snsPasswords_->setAdminPassword('apassword');
        $this->snsPasswords_->setSnsId($sns->getId());
        $em->persist($this->snsPasswords_);
        $em->flush();

        $snsPasswords = $em->getRepository('PMSApiBundle:SnsPasswords')->find($this->snsPasswords_->getId());
        
        $this->assertSame($this->snsPasswords_, $snsPasswords);
        $this->assertSame($sns->getId(), $snsPasswords->getSnsId());
    }
    
    /**
     * @test
     */
    function persistAndReferenced()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        
        $sns = new Sns();
        $sns->setDomain('watanabe.pne.jp');
        $sns->setEmail('watanabepasswordpersistandrefereced@tejimaya.com');
        $sns->setStatus('accepted');
        $sns->setVersion('hosting-3.8.0-c-test');
        $em->persist($sns);
        $em->flush();

        $this->snsPasswords_->setMemberPassword('mpassword');
        $this->snsPasswords_->setAdminPassword('apassword');
        $this->snsPasswords_->setSnsId($sns->getId());
        $em->persist($this->snsPasswords_);
        $em->flush();

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $snsPasswords = $em->getRepository('PMSApiBundle:SnsPasswords')->findOneBy(array('snsId' => $sns->getId()));
        
        $this->assertSame('mpassword', $snsPasswords->getMemberPassword());
        $this->assertSame('apassword', $snsPasswords->getAdminPassword());
        $this->assertSame($sns->getId(), $snsPasswords->getSnsId());
    }
}
