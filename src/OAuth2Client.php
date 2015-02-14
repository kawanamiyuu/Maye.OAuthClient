<?php

namespace Maye\OAuthClient;

use OAuth\OAuth2\Service\ServiceInterface;
use OAuth\OAuth2\Token\StdOAuth2Token;

class OAuth2Client extends AbstractOAuthClient implements OAuth2ClientInterface
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * @var array
     */
    private $extraParams = [];

    /**
     * @param string $serviceName     Service Name
     * @param string $consumerKey     Consumer Key
     * @param string $consumerSecret  Consumer Secret
     * @param string $callbackUrlPath Callback URL Path
     * @param array  $scopes          Scopes
     * @param array  $extraParams     Extra Parameters to Authorize
     */
    public function __construct($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath, array $scopes = [], array $extraParams = [])
    {
        $this->service = (new ServiceFactory)->createService($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath, $scopes);
        $this->extraParams = $extraParams;
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccessToken($code, $state = null)
    {
        return $this->service->requestAccessToken($code, $state);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshAccessToken($refreshToken)
    {
        $token = new StdOAuth2Token();
        $token->setRefreshToken($refreshToken);

        return $this->service->refreshAccessToken($token);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationUrl()
    {
        return $this->service->getAuthorizationUri($this->extraParams);
    }
}
