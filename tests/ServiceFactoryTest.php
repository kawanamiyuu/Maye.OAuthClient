<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\Session;
use OAuth\OAuth1\Service\AbstractService as AbstractOAuth1Service;
use OAuth\OAuth1\Service\Twitter;
use OAuth\OAuth2\Service\AbstractService as AbstractOAuth2Service;
use OAuth\OAuth2\Service\Facebook;

class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCreateOAuth1Service()
    {
        $service = (new ServiceFactory)->createService(
            'twitter',
            '{CONSUMER_KEY}',
            '{CONSUMER_SECRET}',
            '{OAUTH_CALLBACK_URL}'
        );

        $this->assertInstanceOf(AbstractOAuth1Service::class, $service);
        $this->assertInstanceOf(Twitter::class, $service);
        $this->assertInstanceOf(Session::class, $service->getStorage());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateOAuth2Service()
    {
        $service = (new ServiceFactory)->createService(
            'facebook',
            '{CONSUMER_KEY}',
            '{CONSUMER_SECRET}',
            '{OAUTH_CALLBACK_URL}',
            [Facebook::SCOPE_READ_STREAM]
        );

        $this->assertInstanceOf(AbstractOAuth2Service::class, $service);
        $this->assertInstanceOf(Facebook::class, $service);
        $this->assertInstanceOf(Session::class, $service->getStorage());
    }

    public function testCreateCallbackURL()
    {
        $url = (new ServiceFactory)->createCallbackURL('/oauth/twitter/callback');
        $this->assertEquals('http://example.com/oauth/twitter/callback', $url);
    }
}
