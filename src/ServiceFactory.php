<?php

namespace Maye\OAuthClient;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\ServiceFactory as OAuthServiceFactory;

class ServiceFactory
{
    /**
     * @param string                $serviceName     Service Name
     * @param string                $consumerKey     Consumer Key
     * @param string                $consumerSecret  Consumer Secret
     * @param string                $callbackUrlPath Callback URL Path
     * @param array                 $scopes          Scopes (for OAuth2)
     * @param TokenStorageInterface $storage         Token Storage
     *
     * @return ServiceInterface
     */
    public function createService(
        $serviceName,
        $consumerKey,
        $consumerSecret,
        $callbackUrlPath,
        array $scopes = [],
        TokenStorageInterface $storage = null
    ) {
        $callbackUrl = $this->createCallbackURL($callbackUrlPath);
        $credentials = new Credentials($consumerKey, $consumerSecret, $callbackUrl);
        $storage = $storage ?: new Session;

        return (new OAuthServiceFactory)->createService($serviceName, $credentials, $storage, $scopes);
    }

    /**
     * @param string $path Callback URL Path
     *
     * @return string
     */
    public function createCallbackURL($path)
    {
        $uri = (new UriFactory)->createFromSuperGlobalArray($_SERVER);
        $uri->setPath($path);
        $uri->setQuery('');

        return $uri->getAbsoluteUri();
    }
}
