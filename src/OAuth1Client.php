<?php

namespace Kawanamiyuu\OAuthClient;

use OAuth\OAuth1\Service\ServiceInterface;
use OAuth\OAuth1\Token\TokenInterface;

class OAuth1Client extends AbstractOAuthClient implements OAuth1ClientInterface
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
     * @param array  $extraParams     Extra Parameters to Authorize
     */
    public function __construct($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath, array $extraParams = [])
    {
        $this->service = (new ServiceFactory)->createService($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath);
        $this->extraParams = $extraParams;
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccessToken($token, $verifier)
    {
        return $this->service->requestAccessToken($token, $verifier, null);
    }

    /**
     * {@inheritdoc}
     */
    protected function createAuthorizationUrl()
    {
        $requestToken = $this->getRequestToken();

        $params = ['oauth_token' => $requestToken];
        $params += $this->extraParams;

        return $this->service->getAuthorizationUri($params);
    }

    /**
     * Get the Request token
     *
     * @return string Request token
     */
    protected function getRequestToken()
    {
        /** @var TokenInterface $token */
        $token = $this->service->requestRequestToken();
        return $token->getRequestToken();
    }
}
