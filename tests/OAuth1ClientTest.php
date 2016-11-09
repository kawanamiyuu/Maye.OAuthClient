<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\Memory;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;

class OAuth1ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testDefaultStorage()
    {
        $client = $this->getClient(null);
        $storage = $this->getStorage($client);

        $this->assertInstanceOf(Session::class, $storage);
    }

    public function testGetAuthorizationUrl()
    {
        $client = $this->getClient(new Memory);
        $url = $client->getAuthorizationUrl();

        parse_str(parse_url(trim($url))['query'], $queries);

        $this->assertStringStartsWith('https://api.twitter.com/oauth/authenticate?', $url);

        $this->assertEquals([
            'oauth_token' => 'RequestToken',
            'force_login' => 'true'
        ], $queries);
    }

    public function testAuthorize()
    {
        $client = $this->getClient(new Memory);

        ob_start();
        $client->authorize();
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
        $client = $this->getClient(new Memory);
        $storage = $this->getStorage($client);

        $client->setAccessToken('AccessToken', 'TokenSecret');

        /** @var StdOAuth1Token $token */
        $token = $storage->retrieveAccessToken($client->getServiceName());

        $this->assertEquals('AccessToken', $token->getAccessToken());
        $this->assertEquals('TokenSecret', $token->getAccessTokenSecret());
    }

    /**
     * @param TokenStorageInterface $storage
     *
     * @return OAuth1ClientInterface
     */
    private function getClient(TokenStorageInterface $storage = null)
    {
        return new OAuth1TestClient(
            'twitter',
            'ConsumerKey',
            'ConsumerSecret',
            '/oauth/twitter/callback',
            ['force_login' => 'true'],
            $storage
        );
    }

    /**
     * @param OAuthClientInterface $client
     *
     * @return TokenStorageInterface
     */
    private function getStorage(OAuthClientInterface $client)
    {
        $clazz = new \ReflectionClass($client);
        $prop = $clazz->getProperty('service');
        $prop->setAccessible(true);
        $service = $prop->getValue($client);

        return $service->getStorage();
    }
}
