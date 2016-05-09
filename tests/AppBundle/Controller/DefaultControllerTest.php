<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());  // loading
        $this->assertContains('Tetragon', $crawler->filter('h1')->text());  // homepage TODO change to company name
        $this->assertCount(3, $crawler->filter('#main.container button'));  // three buttons
    }
}