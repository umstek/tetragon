<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();  // create a browser

        $crawler = $client->request('GET', '/customers');  // send the request to our page

        $this->assertEquals(200, $client->getResponse()->getStatusCode());  // loading
        $this->assertContains('Customers', $crawler->filter('h1')->text());  // customers page
        $this->assertNotEmpty($crawler->filter('table'));  // table of customers

        $buttons = $crawler->filter('#main.container button');
        $this->assertCount(2, $buttons);  // two buttons
        $this->assertContains('Add Customer', $buttons->first()->text());  // add button
        $this->assertContains('Search Customers', $buttons->last()->text());  // search button

        $crawlerAdd = $client->click($buttons->first()->filter('a')->link());  // click the link inside the button
        //$this->assertContains('Add', $crawlerAdd->filter('h1')->text());  // can navigate to add page

        $crawlerSearch = $client->click($buttons->last()->filter('a')->link());
        //$this->assertContains('Search', $crawlerSearch->filter('h1')->text());

    }
//22 19 3
//22 33 3 10
    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/customers.search');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertContains('Search', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Submit')->form();
        $form->setValues([
            'customer[name]' => 'name1',
            'customer[phone]' => '234'
        ]);
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/customers.add');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/customers/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/customers/1.edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
