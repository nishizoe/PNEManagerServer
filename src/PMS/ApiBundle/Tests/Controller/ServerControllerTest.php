<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PMS\ApiBundle\Entity\Sns;
use PMS\ApiBundle\Entity\Server;

class ServerControllerTest extends WebTestCase
{

    function setUp()
    {
        $client = static::createClient();

        // remove all Servers
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $servers = $em->getRepository('PMSApiBundle:Server')->findAll();
        foreach ($servers as $server)
        {
          $em->remove($server);
        }
        $em->flush();
    }

    /**
     * @test
     */
    function index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/server');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"action":["list","ping","add","detail","update"]}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function listAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/server/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function pingAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/server/ping');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":false}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function pingPongAction()
    {
        $client = static::createClient();

        // add test server
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $server = new Server();
        $server->setHost('pingtest.com');
        $em->persist($server);
        $server = new Server();
        $server->setHost('pingtest.com');
        $em->persist($server);
        $em->flush();

        $crawler = $client->request('GET', '/api/server/ping', array('host' => 'pingtest.com'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function addWebAction()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/server/add', array('host' => 'serveraddtest.com', 'hostType' => 'web'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function addDbAction()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/server/add', array('host' => 'serveraddtest.com', 'hostType' => 'db'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":true}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function addExistAction()
    {
        $client = static::createClient();

        // add test server
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $server = new Server();
        $server->setHost('serveraddtest.com');
        $em->persist($server);
        $em->flush();

        $crawler = $client->request('POST', '/api/server/add', array('host' => 'serveraddtest.com', 'hostType' => 'web'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"result":false}'."\n", $response->getContent());

        $registeredServers = $em->getRepository('PMSApiBundle:Server')->findBy(array('host' => 'serveraddtest.com'));
        $this->assertEquals(1, count($registeredServers));
        $this->assertSame($server, $registeredServers[0]);
    }

    /**
     * @test
     */
    function detailEmpty()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/server/detail', array('host' => 'server.detail.test.com'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function detail()
    {
        $client = static::createClient();

        // add test server
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $server = new Server();
        $server->setHost('server.detail.test.com');
        $em->persist($server);
        $em->flush();

        $crawler = $client->request('GET', '/api/server/detail', array('host' => 'server.detail.test.com'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"domain":[]}'."\n", $response->getContent());
    }

    /**
     * @test
     */
    function detailHavingDomain()
    {
        $client = static::createClient();

        // add test server
        $em = $client->getContainer()->get('doctrine')->getEntityManager();
        $sns = new Sns();
        $sns->setDomain('server.detail.domain.test.com');
        $sns->setEmail('watanabe@tejimaya.com');
        $sns->setStatus('running');
        $em->persist($sns);
        $em->flush();

        $server = new Server();
        $server->setHost('server.detail.test.com');
        $server->getSnss()->add($sns);
        $em->persist($server);
        $em->flush();

        $sns->setServer($server);

        $crawler = $client->request('GET', '/api/server/detail', array('host' => 'server.detail.test.com'));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"domain":["server.detail.domain.test.com"]}'."\n", $response->getContent());
    }

}
