<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Sns;
use PMS\ApiBundle\Entity\Server;
use PMS\ApiBundle\Entity\Account;
use PMS\ApiBundle\Lib\DefaultServerDetermineStrategy;

class SnsControllerTest extends WebTestCase
{

    function setUp()
    {
        $client = static::createClient();

        // remove all Snss
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
     *
     * URI: /api/sns
     * Method: GET
     *
     */
    function index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/sns');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"action":["list","apply","detail"]}'."\n", $response->getContent());
    }

    /**
     * @test
     *
     * URI: /api/sns/list
     * Method: GET
     *
     */
    function listAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/sns/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]'."\n", $response->getContent());
    }

    /**
     * @test
     *
     * URI: /api/sns/list
     * Method: GET
     *
     */
    function listOne()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $sns = new Sns();
        $sns->setDomain('addsns.com');
        $sns->setEmail('watanabe@tejimaya.com');
        $sns->setStatus('running');
        $sds = new DefaultServerDetermineStrategy();
        $em->persist($sns);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/sns/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[{"domain":"addsns.com","status":"running"}]'."\n", $response->getContent());
    }

    /**
     * @test
     *
     * URI: /api/sns/list
     * Method: GET
     *
     */
    function listWithAddDomain()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $sns = new Sns();
        $sns->setDomain('addsns.com');
        $sns->setEmail('watanabe@tejimaya.com');
        $sns->setStatus('running');
        $sds = new DefaultServerDetermineStrategy();
        $em->persist($sns);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/sns/list');
        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[{"domain":"addsns.com","status":"running"}]'."\n", $response->getContent());
    }

    /**
     * @test
     *
     * URI: /api/sns/apply
     * Method: GET
     *
     */
    function apply()
    {
        $client = static::createClient();

        // make server to apply
        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $server = new Server();
        $server->setHost('server.apply.com');
        $em->persist($server);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('POST', '/api/sns/apply', array('domain' => 'apply.watanabe.pne.jp', 'email' => 'watanabe@tejimaya.com'));
        $doctrine = $client->getContainer()->get('doctrine');

        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());

        $sns = $doctrine->getRepository('PMSApiBundle:Sns')->findOneBy(array('domain' => 'apply.watanabe.pne.jp'));
        $domain = $doctrine->getRepository('PMSApiBundle:Domain')->findOneBy(array('domain' => 'apply.watanabe.pne.jp'));
        $account = $doctrine->getRepository('PMSApiBundle:Account')->findOneBy(array('email' => 'watanabe@tejimaya.com'));
        $this->assertEquals('apply.watanabe.pne.jp', $sns->getDomain());
        $this->assertEquals('apply.watanabe.pne.jp', $domain->getDomain());
        $this->assertEquals($server->getHost(), $sns->getServer()->getHost());
        $this->assertequals('watanabe@tejimaya.com', $sns->getAccount()->getEmail());
        $this->assertEquals('sns', $domain->getType());
    }

    /**
     * @test
     *
     * URI: /api/sns/apply
     * Method: POST
     *
     */
    function applyAndList()
    {
        $client = static::createClient();

        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();

        // make server to apply
        $server = new Server();
        $server->setHost('server.apply.com');
        $em->persist($server);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('POST', '/api/sns/apply', array('domain' => 'apply.watanabe.pne.jp', 'email' => 'watanabe@tejimaya.com'));
        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());

        $crawler = $client->request('GET', '/api/sns/list');
        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[{"domain":"apply.watanabe.pne.jp","status":"accepted"}]'."\n", $response->getContent());
    }

    /**
     * @test
     *
     * URI: /api/sns/apply
     * Method: POST
     *
     */
    function applyTwice()
    {
        $client = static::createClient();

        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();

        // make server to apply
        $server = new Server();
        $server->setHost('server.apply.com');
        $em->persist($server);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('POST', '/api/sns/apply', array('domain' => 'apply.watanabe.pne.jp', 'email' => 'watanabe@tejimaya.com'));
        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());

        $crawler = $client->request('POST', '/api/sns/apply', array('domain' => 'apply.watanabe.pne.jp', 'email' => 'watanabe@tejimaya.com'));
        $response = $client->getresponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"error":{"code":400,"message":"the domain already exist."}}'."\n", $response->getContent());

    }

    /**
     * @test
     *
     * URI: /api/sns/detail
     * Method: GET
     * Params: domain
     *
     */
    function detail()
    {
        $client = static::createClient();

        // make server to apply
        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $server = new Server();
        $server->setHost('server.apply.com');
        $em->persist($server);
        $em->flush();
        $account = new Account();
        $account->setEmail('watanabesnsapply@tejimaya.com');
        $em->persist($account);
        $em->flush();
        $sns = new Sns();
        $sns->setDomain('watanabedetail.pne.jp');
        $sns->setEmail('watanabesnsapply@tejimaya.com');
        $sns->setStatus('accepted');
        $em->persist($sns);
        $em->flush();

        $client = static::createClient();

        $crawler = $client->request('GET', '/api/sns/detail', array('domain' => 'watanabedetail.pne.jp'));
        $response = $client->getresponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"domain":"watanabedetail.pne.jp","adminEmail":"watanabesnsapply@tejimaya.com","status":"accepted"}'."\n", $response->getContent());
    }

}
