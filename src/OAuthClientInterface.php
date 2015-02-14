<?php

namespace Maye\OAuthClient;

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
     * @param array  $queries      Query string parameters
     * @param array  $requestBody  Request body parameters
     * @param array  $extraHeaders Extra headers
     *
     * @return string
     */
    public function api($method, $path, array $queries = [], array $requestBody = [], array $extraHeaders = []);
}
