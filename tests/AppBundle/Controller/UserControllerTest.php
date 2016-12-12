<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
  public function testIndex()
  {
    $client = static::createClient();

    $crawler = $client->request('GET', '/user/sign_in');

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertContains('Sign In', $crawler->filter('.container h1')->text());
  }

  public function testSignup()
  {
    $client = static::createClient();

    $crawler = $client->request('GET', '/user/sign_up');

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertContains('Sign Up', $crawler->filter('.container h1')->text());
  }
}
