<?php

namespace Kawanamiyuu\OAuth;

use OAuth\OAuth1\Token\TokenInterface;

interface OAuth1ClientInterface extends OAuthClientInterface
{
    /**
     * @param string $token    OAuth Token
     * @param string $verifier OAuth Verifier
     *
     * @return TokenInterface
     */
    public function requestAccessToken($token, $verifier);
}
