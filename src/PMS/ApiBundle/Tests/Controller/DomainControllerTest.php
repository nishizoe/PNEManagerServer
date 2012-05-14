<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Domain;

class DomainControllerTest extends WebTestCase
{

    function setUp()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        // remove all Domains
        $domains = $em->getRepository('PMSApiBundle:Domain')->findAll();
        foreach ($domains as $domain)
        {
          $em->remove($domain);
        }
        $em->flush();

    }

    /**
     * @test
     */
    function index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/domain');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"action":["list","add","available"]}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function listAction()
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

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/domain/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function add()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/domain/add', array('domain' => 'example.com', 'domainType' => 'sns'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function availableEmpty()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $crawler = $client->request('GET', '/api/domain/available');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":false}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function availableNotExist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $crawler = $client->request('GET', '/api/domain/available', array('domain' => 'watanabe.pne.jp'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function availableExist()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        // ss onw domain
        $domain = new Domain();
        $domain->setDomain('watanabe.pne.jp');
        $domain->setType('sns');
        $em->persist($domain);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/domain/available', array('domain' => 'watanabe.pne.jp'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":false}'."\n", $response->getContent());
    }

}
