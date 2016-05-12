<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalesInventoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sales/');
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selling_items.add');
    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selling_items/{id}.view');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selling_items/{id}.edit');
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selling_items.search');
    }

}
