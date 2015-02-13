<?php

namespace Kawanamiyuu\OAuthClient;

use OAuth\Common\Service\ServiceInterface;

abstract class AbstractOAuthClient implements OAuthClientInterface
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * Get an Authorization URL
     *
     * @return string
     */
    abstract protected function getAuthorizationUrl();

    /**
     * {@inheritdoc}
     */
    public function authorize()
    {
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
}
