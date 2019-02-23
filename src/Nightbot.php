<?php

namespace Androidbeet\LaravelNightbot;

class Nightbot
{
    protected $authUrl = 'https://api.nightbot.tv/oauth2/authorize';
    protected $tokenUrl = 'https://api.nightbot.tv/oauth2/token';

    /**
     * Return url for Nightbot OAuth2 authorization
     *
     * @param bool $withState
     * @return string
     */
    public function getAuthURL($withState = true)
    {
        $url = $this->authUrl .
            '?response_type=' . config('nightbot.response_type') .
            '&client_id=' . config('nightbot.client_id') .
            '&redirect_uri=' . urlencode(config('nightbot.redirect_url')) .
            '&scope=' . implode(' ', config('nightbot.scopes'));

        if ($withState) {
            $url .= '&state=' . config('nightbot.state');
        }

        dd($url);

        return $url;
    }
}
