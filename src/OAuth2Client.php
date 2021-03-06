<?php

namespace Maye\OAuthClient;

use OAuth\Common\Storage\TokenStorageInterface;
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
     * @param string                $serviceName     Service Name
     * @param string                $consumerKey     Consumer Key
     * @param string                $consumerSecret  Consumer Secret
     * @param string                $callbackUrlPath Callback URL Path
     * @param array                 $scopes          Scopes
     * @param array                 $extraParams     Extra Parameters to Authorize
     * @param TokenStorageInterface $storage         Token Storage
     */
    public function __construct($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath, array $scopes = [], array $extraParams = [], TokenStorageInterface $storage = null)
    {
        $this->service = (new ServiceFactory)->createService($serviceName, $consumerKey, $consumerSecret, $callbackUrlPath, $scopes, $storage);
        $this->extraParams = $extraParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUrl()
    {
        return (string) $this->service->getAuthorizationUri($this->extraParams);
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
    public function setAccessToken($accessToken)
    {
        $this->clearCachedAccessToken();

        $token = new StdOAuth2Token;
        $token->setAccessToken($accessToken);

        /** @var TokenStorageInterface $storage */
        $storage = $this->service->getStorage();
        $storage->storeAccessToken($this->getServiceName(), $token);

        return $this;
    }
}
