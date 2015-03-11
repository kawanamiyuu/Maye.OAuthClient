<?php

namespace Maye\OAuthClient;

use OAuth\OAuth2\Service\Facebook;

class OAuth2ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OAuth2ClientInterface
     */
    private $client;

    public function setUp()
    {
        $this->client = new OAuth2Client(
            'facebook',
            'ConsumerKey',
            'ConsumerSecret',
            '/oauth/facebook/callback',
            [Facebook::SCOPE_READ_STREAM, Facebook::SCOPE_PUBLISH_ACTIONS],
            ['auth_type' => 'reauthenticate']
        );
    }

    public function testAuthorize()
    {
        ob_start();
        $this->client->authorize();
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
}
