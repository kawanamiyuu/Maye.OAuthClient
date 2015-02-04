<?php

namespace Kawanamiyuu\OAuthClient;

class OAuth1TestClient extends OAuth1Client
{
    public function getRequestToken()
    {
        return 'RequestToken';
    }
}

class OAuth1ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OAuth1ClientInterface
     */
    private $client;

    public function setUp()
    {
        $this->client = new OAuth1TestClient(
            'twitter',
            '{C_KEY}',
            '{C_SECRET}',
            '/oauth/twitter/callback',
            ['force_login' => 'true']
        );
    }

    public function testAuthorize()
    {
        ob_start();
        $this->client->authorize();
        $result = ob_get_clean();

        $expected = 'Location: https://api.twitter.com/oauth/authenticate?oauth_token=RequestToken&force_login=true';
        $this->assertEquals($expected, $result);
    }
} 
