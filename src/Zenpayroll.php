<?php

namespace Wheniwork\OAuth2\Client\Provider;

use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Zenpayroll extends AbstractProvider
{
    /**
     * Enable debugging by connecting to the ZenPayroll demo server.
     *
     * @var boolean
     */
    public $debug = false;

    /**
     * Get a ZenPayroll API URL, depending on path.
     *
     * @param  string $path
     * @return string
     */
    protected function getApiUrl($path)
    {
        $demo = $this->debug ? '-demo' : '';
        return "https://zenpayroll{$demo}.com/{$path}";
    }

    public function urlAuthorize()
    {
        return $this->getApiUrl('oauth/authorize');
    }

    public function urlAccessToken()
    {
        return $this->getApiUrl('oauth/token');
    }

    public function urlUserDetails(AccessToken $token)
    {
        return $this->getApiUrl('api/v1/me');
    }

    public function userDetails($response, AccessToken $token)
    {
        $user = new User([
            'email' => $response->email,
        ]);
        return $user;
    }

    protected function fetchUserDetails(AccessToken $token)
    {
        $this->headers['Authorization'] = 'Token ' . $token->accessToken;
        $this->headers['Content-Type']  = 'application/json';

        return parent::fetchUserDetails($token);
    }
}
