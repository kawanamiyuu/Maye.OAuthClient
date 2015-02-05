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
     * Send an API Request
     *
     * @param string $method HTTP method
     * @param string $path   Resource path
     * @param array  $params Query parameters (GET) or Request body parameters (POST)
     * @param array  $extraHeaders
     *
     * @return string
     */
    public function api($method, $path, array $params = [], array $extraHeaders = [])
    {
        $method = strtolower($method);

        if ($method === 'put' || $method === 'delete') {
            $extraHeaders = array_change_key_case($extraHeaders, CASE_LOWER);
            $extraHeaders['x-http-method-override'] = $method;
            $method = 'post';
        }

        if ($method === ' post') {
            return $this->service->request($path, $method, $params, $extraHeaders);
        }

        $path .= empty($params) ? '' : '?' . http_build_query($params);
        return $this->service->request($path, $method, null, $extraHeaders);

    }
}
