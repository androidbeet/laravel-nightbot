<?php

namespace Androidbeet\Nightbot;

use Androidbeet\Nightbot\Models\NightbotToken;

class Nightbot
{
    public $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.nightbot.tv/1/'
        ]);
    }

    /**
     * Return url for Nightbot OAuth2 authorization
     * Step 2 -> https://api-docs.nightbot.tv/#authorization-code-flow
     *
     * @param bool $withState
     * @return string
     */
    public function getAuthURL($withState = true)
    {
        $url = 'https://api.nightbot.tv/oauth2/authorize' .
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
     * @return array
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

        $response = $this->client->request('POST', 'https://api.nightbot.tv/oauth2/token', [
            'form_params' => $parameters,
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * GET OAuth2 Access Token from Database
     *
     * @param int $id
     * @return mixed
     */
    public function getToken(int $id)
    {
        $result = NightbotToken::findOrfail($id);

        return $result->access_token;
    }

    /**
     * ==============================================================================================================
     *  CHANNELS
     *  https://api-docs.nightbot.tv/#channel
     *  The channel methods allow you to get information about the channel as well as join and part the bot.
     * ==============================================================================================================
     */

    /**
     * Gets the API user’s channel information
     * Scope -> channel
     * https://api-docs.nightbot.tv/#get-channel
     *
     * @param string $token
     * @return array
     */
    public function getChannel(string $token)
    {
        $response = $this->client->request('GET', 'channel', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Makes Nightbot join (enter) the current user’s channel
     * Scope -> channel
     * https://api-docs.nightbot.tv/#join-channel
     *
     * @param string $token
     * @return array
     */
    public function joinChannel(string $token)
    {
        $response = $this->client->request('POST', 'channel/join', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Makes Nightbot part (leave) the current user’s channel
     * Scope -> channel
     * https://api-docs.nightbot.tv/#part-channel
     *
     * @param string $token
     * @return array
     */
    public function partChannel(string $token)
    {
        $response = $this->client->request('POST', 'channel/part', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Makes Nightbot send a message to the channel
     * Scope -> channel_send
     * https://api-docs.nightbot.tv/#send-channel-message
     *
     * @param string $token
     * @param string $message
     * @return array
     */
    public function sendChannelMessage(string $token, string $message)
    {
        $response = $this->client->request('POST', 'channel/send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'form_params' => [
                'message' => $message,
            ]
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * ==============================================================================================================
     *  TIMERS
     *  https://api-docs.nightbot.tv/#timers
     *  The timers methods allow you to view, add, edit, and remove channel timers.
     * ==============================================================================================================
     */

    /**
     * Gets the current API user’s timers list
     * Scope -> timers
     * https://api-docs.nightbot.tv/#part-channel
     *
     * @param string $token
     * @return array
     */
    public function getTimers(string $token)
    {
        $response = $this->client->request('GET', 'timers', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Adds a new timer to the current user’s channel
     * Scope -> timers
     * https://api-docs.nightbot.tv/#add-new-timer
     *
     * @param string $token
     * @param array $parameters
     *  -> enabled (boolean) Optional
     *      The status of the timer. A value of true means the timer is enabled, while false means
     *      the timer is disabled and will not execute.
     * -> interval (string) Required
     *      The timer interval in cron syntax. Minimum interval is once per 5 minutes.
     * -> lines (number) Required
     *      This is the minimum amount of chat lines per 5 minutes required to activate the timer.
     *      This is useful in slow chats to prevent Nightbot from spamming in an empty channel.
     * -> message (string) Required
     *      The message to send to chat. It can contain variables for extra functionality.
     *      Maximum length: 400 characters
     * -> name (string) Required
     *      The timer name (has no real significance but gives you a quick reference to what the timer does)
     * @return array
     */
    public function addNewTimer(string $token, array $parameters)
    {
        $response = $this->client->request('POST', 'timers', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'form_params' => $parameters,
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Looks up a timer by id
     * Scope -> timers
     * https://api-docs.nightbot.tv/#get-timer-by-id
     *
     * @param string $token
     * @param string $id
     * @return array
     */
    public function getTimerById(string $token, string $id)
    {
        $response = $this->client->request('POST', "timers/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Edits a timer by its id.
     * Scope -> timers
     * https://api-docs.nightbot.tv/#edit-timer-by-id
     *
     * @param string $token
     * @param array $parameters
     *  -> enabled (boolean) Optional
     *      The status of the timer. A value of true means the timer is enabled, while false means
     *      the timer is disabled and will not execute.
     * -> interval (string) Optional
     *      The timer interval in cron syntax. Minimum interval is once per 5 minutes.
     * -> lines (number) Optional
     *      This is the minimum amount of chat lines per 5 minutes required to activate the timer.
     *      This is useful in slow chats to prevent Nightbot from spamming in an empty channel.
     * -> message (string) Optional
     *      The message to send to chat. It can contain variables for extra functionality.
     *      Maximum length: 400 characters
     * -> name (string) Optional
     *      The timer name (has no real significance but gives you a quick reference to what the timer does)
     * @return array
     */
    public function editTimerById(string $token, string $id, array $parameters)
    {
        $response = $this->client->request('PUT', "timers/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'form_params' => $parameters,
        ])->getBody();

        return json_decode($response->getContents(), true);
    }

    /**
     * Deletes a timer by id
     * Scope -> timers
     * https://api-docs.nightbot.tv/#get-timer-by-id
     *
     * @param string $token
     * @param string $id
     * @return array
     */
    public function deleteTimerById(string $token, string $id)
    {
        $response = $this->client->request('DELETE', "timers/{$id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ])->getBody();

        return json_decode($response->getContents(), true);
    }


}
