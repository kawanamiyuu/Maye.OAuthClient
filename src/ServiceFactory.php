<?php

namespace Kawanamiyuu\OAuthClient;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\Session;
use OAuth\ServiceFactory as OAuthServiceFactory;

class ServiceFactory
{
    /**
     * @param string                $serviceName       Service Name
     * @param string                $consumerKey       Consumer Key
     * @param string                $consumerSecret    Consumer Secret
     * @param string                $callbackUrlPath   Callback URL Path
     * @param array                 $scopes            Scopes (for OAuth2)
     *
     * @return ServiceInterface
     */
    public function createService(
        $serviceName,
        $consumerKey,
        $consumerSecret,
        $callbackUrlPath,
        array $scopes = []
    )
    {
        $callbackUrl = $this->createCallbackURL($callbackUrlPath);
        $credentials = new Credentials($consumerKey, $consumerSecret, $callbackUrl);
        return (new OAuthServiceFactory)->createService($serviceName, $credentials, new Session, $scopes);
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
