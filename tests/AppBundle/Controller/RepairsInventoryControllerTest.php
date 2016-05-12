<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepairsInventoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/');
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items.search');
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items.add');
    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/{id}.view');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/repair_items/{id}.edit');
    }

}
