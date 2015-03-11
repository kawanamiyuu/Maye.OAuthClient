<?php

namespace Maye\OAuthClient;

use OAuth\OAuth1\Token\TokenInterface;

interface OAuth1ClientInterface extends OAuthClientInterface
{
    /**
     * Retrieve an OAuth1 access token
     *
     * @param string $token    OAuth Token
     * @param string $verifier OAuth Verifier
     *
     * @return TokenInterface
     */
    public function requestAccessToken($token, $verifier);

    /**
     * Set Access token and Token secret
     *
     * @param string $accessToken Access token
     * @param string $tokenSecret Token secret
     *
     * @return self
     */
    public function setAccessToken($accessToken, $tokenSecret);
}
