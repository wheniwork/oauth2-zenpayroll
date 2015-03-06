# Zenpayroll Provider for OAuth 2.0 Client

[![Build Status](https://img.shields.io/travis/wheniwork/oauth2-zenpayroll.svg)](https://travis-ci.org/wheniwork/oauth2-zenpayroll)
[![Code Coverage](https://img.shields.io/coveralls/wheniwork/oauth2-zenpayroll.svg)](https://coveralls.io/r/wheniwork/oauth2-zenpayroll)
[![Code Quality](https://img.shields.io/scrutinizer/g/wheniwork/oauth2-zenpayroll.svg)](https://scrutinizer-ci.com/g/wheniwork/oauth2-zenpayroll/)
[![License](https://img.shields.io/packagist/l/wheniwork/oauth2-zenpayroll.svg)](https://github.com/wheniwork/oauth2-zenpayroll/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/wheniwork/oauth2-zenpayroll.svg)](https://packagist.org/packages/wheniwork/oauth2-zenpayroll)

This package provides Zenpayroll OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require wheniwork/oauth2-zenpayroll
```

## Usage

Usage is the same as The League's OAuth client, using `Wheniwork\OAuth2\Client\Provider\Zenpayroll` as the provider.

### Authorization Code Flow

```php
$provider = new Wheniwork\OAuth2\Client\Provider\Zenpayroll([
    'clientId'          => '{zenpayroll-client-id}',
    'clientSecret'      => '{zenpayroll-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);

        // Use these details to create a new profile
        printf('Hello %s!', $userDetails->firstName);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->accessToken;
}
```

## Refreshing a Token

```php
$provider = new Wheniwork\OAuth2\Client\Provider\Zenpayroll([
    'clientId'          => '{zenpayroll-client-id}',
    'clientSecret'      => '{zenpayroll-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

$grant = new \League\OAuth2\Client\Grant\RefreshToken();
$token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
```
