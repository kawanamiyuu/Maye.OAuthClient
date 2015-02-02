<?php

namespace Kawanamiyuu\OAuth;

interface OAuthClientInterface
{
    /**
     * Redirect to authorize
     *
     * @return void
     */
    public function authorize();

    /**
     * Send an API Request
     *
     * @param string $method       HTTP method
     * @param string $path         Resource path
     * @param array  $params       Query parameters (GET) or Request body parameters (POST)
     * @param array  $extraHeaders Extra headers
     *
     * @return string
     */
    public function api($method, $path, array $params = [], array $extraHeaders = []);
}
