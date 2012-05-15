<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Account;

class AccountControllerTest extends WebTestCase
{

    function setUp()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        // remove all datas
        $snss = $em->getRepository('PMSApiBundle:Sns')->findAll();
        foreach ($snss as $sns)
        {
          $em->remove($sns);
        }
        $domains = $em->getRepository('PMSApiBundle:Account')->findAll();
        foreach ($domains as $domain)
        {
          $em->remove($domain);
        }
        $em->flush();
    }

    /**
     * @test
     *
     * URI: /api/account
     * Method: GET
     *
     */
    function index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/account');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"action":["list"]}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function listEmptyTest()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/account/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function listTest()
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getEntityManager();

        $account = new Account();
        $account->setEmail('watanabe+t1@tejimaya.com');
        $em->persist($account);

        $account = new Account();
        $account->setEmail('watanabe+t2@tejimaya.com');
        $em->persist($account);

        $account = new Account();
        $account->setEmail('watanabe+t3@tejimaya.com');
        $em->persist($account);

        $em->flush();

        $client = static::createClient();
        $crawler = $client->request('GET', '/api/account/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[{"email":"watanabe+t1@tejimaya.com"},{"email":"watanabe+t2@tejimaya.com"},{"email":"watanabe+t3@tejimaya.com"}]'."\n", $response->getContent());
    }

}
