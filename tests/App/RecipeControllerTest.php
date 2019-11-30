<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    public function testLunch()
    {
        $client = static::createClient();

        $client->request('GET', '/lunch');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
