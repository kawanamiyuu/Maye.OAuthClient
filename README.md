Maye.OAuthClient
====

Maye.OAuthClient is the OAuth Client library which has simple interface to support [OAuth 1](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth1/Service) and [OAuth 2](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service), based on [lusitanian/oauth (PHPoAuthLib)](https://github.com/Lusitanian/PHPoAuthLib).

## example: Twitter OAuth

### Redirects to an authorization page

```php
use Maye\OAuthClient;

define('CONSUMER_KEY', 'your twitter consumer key');
define('CONSUMER_SECRET', 'your twitter consumer secret');

$client = new OAuth1Client('twitter', CONSUMER_KEY, CONSUMER_SECRET, '/oauth/twitter/callback');

// redirect
$client->authorize();
exit;
```

### Called back after authorization process finished

```php
$denied = $_GET['denied'];
$oauthToken = $_GET['oauth_token'];
$oauthVerifier = $_GET['oauth_verifier'];

if (!empty($denied)) {
	// when the user has canceled at Twitter authorization page
	echo 'denied.';
	exit;
}

/** @var OAuth\OAuth1\Token\TokenInterface $token */
$token = $client->requestAccessToken($oauthToken, $oauthVerifier);

$accessToken       = $token->getAccessToken();
$accessTokenSecret = $token->getAccessTokenSecret();
$userId     = $token->getExtraParams()['user_id'];
$screenName = $token->getExtraParams()['screen_name'];
```
