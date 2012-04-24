<?php

namespace PMS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"type":["domain","server","sns"]}'."\n", $response->getContent());
    }

    public function testApiTypeError()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api?type=DOMAIN');
        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"error":{"code":400,"message":{"type":["invalid"]}}}'."\n", $response->getContent());
    }

    public function testApiActionError()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api?type=domain&action=LIST');
        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"error":{"code":400,"message":{"action":["invalid"]}}}'."\n", $response->getContent());
    }

}
