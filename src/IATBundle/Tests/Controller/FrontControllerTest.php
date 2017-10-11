<?php

namespace IATBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public function testGetdeal()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getDeal');
    }

}
