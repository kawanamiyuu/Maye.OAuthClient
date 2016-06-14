<?php

namespace Maye\OAuthClient;

class OAuth1TestClient extends OAuth1Client
{
    protected function getRequestToken()
    {
        return 'RequestToken';
    }
}
