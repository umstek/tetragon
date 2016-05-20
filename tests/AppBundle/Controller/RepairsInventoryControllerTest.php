<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepairsInventoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items.search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items.add');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/1');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/1.edit');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
