<?php

namespace Maye\OAuthClient;

use OAuth\OAuth2\Service\Exception\MissingRefreshTokenException;
use OAuth\OAuth2\Token\TokenInterface;

interface OAuth2ClientInterface extends OAuthClientInterface
{
    /**
     * Retrieve an OAuth2 access token
     *
     * @param string $code  Code
     * @param string $state State
     *
     * @return TokenInterface
     */
    public function requestAccessToken($code, $state = null);

    /**
     * Refresh an OAuth2 access token.
     *
     * @param string $refreshToken
     *
     * @return TokenInterface $token
     *
     * @throws MissingRefreshTokenException
     */
    public function refreshAccessToken($refreshToken);

    /**
     * Set Access token
     *
     * @param string $accessToken Access token
     *
     * @return self
     */
    public function setAccessToken($accessToken);
}
