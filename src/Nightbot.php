<?php

namespace Androidbeet\Nightbot;

use Androidbeet\Nightbot\Models\NightbotToken;

class Nightbot
{
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

        return json_decode($result, true);
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
     * Send GET, POST requests
     *
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return mixed
     */
    protected function curlRequest(string $method = 'GET', string $url, array $parameters = [], array $headers = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

            $additionalHeaders = [
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $headers = array_merge($headers, $additionalHeaders);
        }

        if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

            $additionalHeaders = [
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $headers = array_merge($headers, $additionalHeaders);
        }

        if ($method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

            $additionalHeaders = [
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $headers = array_merge($headers, $additionalHeaders);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('GET', 'https://api.nightbot.tv/1/channel', [], $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/1/channel/join', [], $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/1/channel/part', [], $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $parameters = [
            'message' => $message,
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/1/channel/send', $parameters, $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('GET', 'https://api.nightbot.tv/1/timers', [], $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/1/timers', $parameters, $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('POST', 'https://api.nightbot.tv/1/timers/' . $id, [], $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('PUT', 'https://api.nightbot.tv/1/timers/' . $id, $parameters, $headers);

        return json_decode($result, true);
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
        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $result = $this->curlRequest('DELETE', 'https://api.nightbot.tv/1/timers/' . $id, [], $headers);

        return json_decode($result, true);
    }


}
