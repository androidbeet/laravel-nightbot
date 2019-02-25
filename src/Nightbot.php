<?php

namespace Androidbeet\Nightbot;
use Illuminate\Support\Facades\Config;

class Nightbot
{
    protected $authUrl = 'https://api.nightbot.tv/oauth2/authorize';
    protected $tokenUrl = 'https://api.nightbot.tv/oauth2/token';

    /**
     * Return url for Nightbot OAuth2 authorization
     * Step 2 -> https://api-docs.nightbot.tv/#authorization-code-flow
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

        return $url;
    }

    /**
     * Return access token with other data
     * Step 4 -> https://api-docs.nightbot.tv/#authorization-code-flow
     *
     * @param string $code
     * @return mixed
     */
    public function getTokenData(string $code)
    {
        $parameters = [
            'client_id' => config('nightbot.client_id'),
            'client_secret' => config('nightbot.secret_id'),
            'code' => $code,
            'grant_type' => config('nightbot.grant_type'),
            'redirect_uri' => config('nightbot.redirect_url'),
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/oauth2/token', $parameters);

        return $result;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @return array
     */
    protected function curlRequest(string $method = 'GET', string $url, array $parameters = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);
    }
}
