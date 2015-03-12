<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\Session;
use OAuth\OAuth1\Token\StdOAuth1Token;

class OAuth1TestClient extends OAuth1Client
{
    protected function getRequestToken()
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
            'ConsumerKey',
            'ConsumerSecret',
            '/oauth/twitter/callback',
            ['force_login' => 'true']
        );
    }

    public function testAuthorize()
    {
        ob_start();
        $this->client->authorize();
        $result = ob_get_clean();

        list(, $url) = explode(':', $result, 2);
        parse_str(parse_url(trim($url))['query'], $queries);

        $this->assertStringStartsWith('Location: https://api.twitter.com/oauth/authenticate?', $result);

        $this->assertEquals([
            'oauth_token' => 'RequestToken',
            'force_login' => 'true'
        ], $queries);
    }

    public function testSetAccessToken()
    {
        $this->client->setAccessToken('AccessToken', 'TokenSecret');

        /** @var StdOAuth1Token $token */
        $token = (new Session)->retrieveAccessToken($this->client->getServiceName());

        $this->assertEquals('AccessToken', $token->getAccessToken());
        $this->assertEquals('TokenSecret', $token->getAccessTokenSecret());
    }
}
