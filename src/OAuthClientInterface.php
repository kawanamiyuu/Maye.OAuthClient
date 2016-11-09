<?php

namespace Maye\OAuthClient;

interface OAuthClientInterface
{
    /**
     * Get an Authorization URL
     *
     * @return string
     */
    public function getAuthorizationUrl();

    /**
     * Redirect to authorize
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

    /**
     * Get the service name
     *
     * @return string
     */
    public function getServiceName();
}
