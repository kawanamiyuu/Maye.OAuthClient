Maye.OAuthClient
====

[![Build Status](https://travis-ci.org/kawanamiyuu/Maye.OAuthClient.svg?branch=master)](https://travis-ci.org/kawanamiyuu/Maye.OAuthClient)

Maye.OAuthClient is the OAuth Client library which has simple interface to support [OAuth 1](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth1/Service) and [OAuth 2](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service), based on [lusitanian/oauth (PHPoAuthLib)](https://github.com/Lusitanian/PHPoAuthLib).

## e.g. Twitter (OAuth1)

### 1. Redirects to an authorization page

```php
use Maye\OAuthClient;

define('CONSUMER_KEY', 'your twitter consumer key');
define('CONSUMER_SECRET', 'your twitter consumer secret');

$client = new OAuth1Client(
	'twitter', 
	CONSUMER_KEY, 
	CONSUMER_SECRET, 
	'/oauth/twitter/callback'
);

// redirect
$client->authorize();
exit;
```

### 2. Called back after authorization process finished

```php
$denied = $_GET['denied'];
$oauthToken = $_GET['oauth_token'];
$oauthVerifier = $_GET['oauth_verifier'];

if (!empty($denied)) {
	// should be handled as error
	echo 'error';
	exit;
}

/** @var OAuth\OAuth1\Token\TokenInterface $token */
$token = $client->requestAccessToken($oauthToken, $oauthVerifier);

$accessToken       = $token->getAccessToken();
$accessTokenSecret = $token->getAccessTokenSecret();
$userId     = $token->getExtraParams()['user_id'];
$screenName = $token->getExtraParams()['screen_name'];

// gets the authorized user info
$result = $client->api('get', 'users/show.json', ['user_id' => $userId]);
$result = json_decode($result);

$name = $result->name;
```

## e.g. Facebook (OAuth2)

### 1. Redirects to authorization page

```php
use Maye\OAuthClient;
use OAuth\OAuth2\Service\Facebook;

define('CONSUMER_KEY', 'your facebook consumer key');
define('CONSUMER_SECRET', 'your facebook consumer secret');

$client = new OAuth2Client(
	'facebook',
	CONSUMER_KEY, 
	CONSUMER_SECRET, 
	'/oauth/facebook/callback',
	[Facebook::SCOPE_READ_STREAM, Facebook::SCOPE_PUBLISH_ACTIONS]
);

// redirect
$client->authorize();
exit;
```

### 2. Called back after authorization process finished

```php
$code = $_GET['code'];

if (empty($code)) {
	// should be handled as error
	echo 'error';
	exit;
}

/** @var OAuth\OAuth2\Token\TokenInterface $token */
$token = $client->requestAccessToken($code);

$accessToken  = $token->getAccessToken();
$refreshToken = $token->getRefreshToken();

// gets the authorized user info
$result = $client->api('get', '/me');
$result = json_decode($result);

$id = $result->id;
$name = $result->name;
```
