<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\Memory;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth2\Service\Facebook;
use OAuth\OAuth2\Token\StdOAuth2Token;

class OAuth2ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultStorage()
    {
        $client = $this->getClient(null);
        $storage = $this->getStorage($client);

        $this->assertInstanceOf(Session::class, $storage);
    }
    
    public function testAuthorize()
    {
        $client = $this->getClient(new Memory);
        
        ob_start();
        $client->authorize();
        $result = ob_get_clean();

        list(, $url) = explode(':', $result, 2);
        parse_str(parse_url(trim($url))['query'], $queries);

        $this->assertStringStartsWith('Location: https://www.facebook.com/dialog/oauth?', $result);

        $this->assertEquals([
            'client_id'     => 'ConsumerKey',
            'redirect_uri'  => 'http://example.com/oauth/facebook/callback',
            'scope'         => 'read_stream publish_actions',
            'auth_type'     => 'reauthenticate',
            'type'          => 'web_server',
            'response_type' => 'code'
        ], $queries);
    }

    public function testSetAccessToken()
    {
        $client = $this->getClient(new Memory);
        $storage = $this->getStorage($client);

        $client->setAccessToken('AccessToken');

        /** @var StdOAuth2Token $token */
        $token = $storage->retrieveAccessToken($client->getServiceName());

        $this->assertEquals('AccessToken', $token->getAccessToken());
    }

    /**
     * @param TokenStorageInterface $storage
     *
     * @return OAuth2ClientInterface
     */
    private function getClient(TokenStorageInterface $storage = null) {
        return new OAuth2Client(
            'facebook',
            'ConsumerKey',
            'ConsumerSecret',
            '/oauth/facebook/callback',
            [Facebook::SCOPE_READ_STREAM, Facebook::SCOPE_PUBLISH_ACTIONS],
            ['auth_type' => 'reauthenticate'],
            $storage
        );
    }

    /**
     * @param OAuthClientInterface $client
     *
     * @return TokenStorageInterface
     */
    private function getStorage(OAuthClientInterface $client) {
        $clazz = new \ReflectionClass($client);
        $prop = $clazz->getProperty('service');
        $prop->setAccessible(true);
        $service = $prop->getValue($client);

        return $service->getStorage();
    }
}
