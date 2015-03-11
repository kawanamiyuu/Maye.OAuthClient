<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Service\ServiceInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
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
    public function setAccessToken($accessToken, $tokenSecret)
    {
        $token = new StdOAuth1Token;
        $token->setAccessToken($accessToken);
        $token->setAccessTokenSecret($tokenSecret);

        /** @var TokenStorageInterface $storage */
        $storage = $this->service->getStorage();
        $storage->storeAccessToken($this->service->service(), $token);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationUrl()
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
