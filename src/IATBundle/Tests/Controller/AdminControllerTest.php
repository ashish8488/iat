<?php

namespace IATBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testRegistermerchant()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registerMerchant');
    }

    public function testUploaddeals()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/uploadDeals');
    }

    public function testListinquiry()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listInquiry');
    }

}
