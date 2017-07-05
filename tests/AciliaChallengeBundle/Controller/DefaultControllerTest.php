<?php

namespace Tests\AciliaChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('LA MOLINA', $client->getResponse()->getContent());
        $this->assertContains('ESTACA DE BARES', $client->getResponse()->getContent());
        $this->assertContains('CABO BUSTO', $client->getResponse()->getContent());
    }
}
