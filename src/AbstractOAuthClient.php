<?php

namespace Maye\OAuthClient;

use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\TokenStorageInterface;

abstract class AbstractOAuthClient implements OAuthClientInterface
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * {@inheritdoc}
     */
    abstract public function getAuthorizationUrl();

    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
        $this->clearCachedAccessToken();

        header('Location: ' . $this->getAuthorizationUrl());
    }

    /**
     * {@inheritdoc}
     */
    public function api($method, $path, array $queries = [], array $requestBody = [], array $extraHeaders = [])
    {
        $path .= empty($queries) ? '' : '?' . http_build_query($queries);
        $requestBody = empty($requestBody) ? null : $requestBody;

        return $this->service->request($path, $method, $requestBody, $extraHeaders);
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceName()
    {
        return $this->service->service();
    }

    /**
     * clear Cached Access token
     *
     * @return self
     */
    protected function clearCachedAccessToken()
    {
        /** @var TokenStorageInterface $storage */
        $storage = $this->service->getStorage();
        $storage->clearToken($this->getServiceName());

        return $this;
    }
}
