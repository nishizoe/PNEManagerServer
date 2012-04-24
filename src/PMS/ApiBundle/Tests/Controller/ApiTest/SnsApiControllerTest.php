<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Domain;

class SnsApiControllerTest extends WebTestCase
{

    public function setUp()
    {
        $client = static::createClient();

        // remove all Domains
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $domains = $em->getRepository('PMSApiBundle:Domain')->findAll();
        foreach ($domains as $domain)
        {
          $em->remove($domain);
        }
        $em->flush();
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api?type=domain');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"action":["list","add","available"]}'."\n", $response->getContent());
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api?type=domain&action=add', array('domain' => 'watanabe.pne.jp', 'domainType' => 'sns'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    public function testAvailableNotExist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api?type=domain&action=available', array('domain' => 'notfound.watanabe.pne.jp'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    public function testAvailableAvailable()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        // ss onw domain
        $domain = new Domain();
        $domain->setDomain('available.watanabe.pne.jp');
        $domain->setType('sns');
        $em->persist($domain);
        $em->flush();

        $crawler = $client->request('GET', '/api?type=domain&action=available', array('domain' => 'available.watanabe.pne.jp'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":false}'."\n", $response->getContent());
    }

}
