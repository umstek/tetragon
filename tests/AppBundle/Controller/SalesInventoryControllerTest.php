<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalesInventoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient(); // create a browser

        $crawler = $client->request('GET', '/selling_items');   // send the request to our page

        $this->assertEquals(200, $client->getResponse()->getStatusCode());  // loading
        $this->assertContains('Sales inventory', $crawler->filter('h1')->text());  // Sales inventory page
        $this->assertNotEmpty($crawler->filter('table'));  // table of selling items

        $buttons = $crawler->filter('#main.container button');
        $this->assertCount(2, $buttons);  // two buttons
        $this->assertContains('Add item', $buttons->first()->text());  // add button
        $this->assertContains('Search items', $buttons->last()->text());  // search button

        $crawlerAdd = $client->click($buttons->first()->filter('a')->link());  // click the link inside the button
        //$this->assertContains('Add', $crawlerAdd->filter('h1')->text());  // can navigate to add page

        $crawlerSearch = $client->click($buttons->last()->filter('a')->link());
        //$this->assertContains('Search', $crawlerSearch->filter('h1')->text());
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
