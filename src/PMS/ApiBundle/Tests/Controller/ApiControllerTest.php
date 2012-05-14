<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"type":["domain","server","sns"]}'."\n", $response->getContent());
    }

}
