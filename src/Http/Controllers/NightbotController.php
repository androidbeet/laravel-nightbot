<?php

namespace Androidbeet\Nightbot\Http\Controllers;

use Androidbeet\Nightbot\Facades\Nightbot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Androidbeet\Nightbot\Models\NightbotToken;


class NightbotController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->token = NightbotToken::class;
    }

    /**
     * Show Auth Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAuthPage()
    {
          $token = $this->token::id(3);

        $url = Nightbot::getAuthURL();

        $email = config('nightbot.contact_email');

        return view('nightbot::twitch.auth', compact('url', 'email'));
    }

    /**
     * Show Twitch Success Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTwitchSuccessPage()
    {
        return view('nightbot::twitch.success');
    }

    /**
     * Catch and Store data from callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function catchCallback(Request $request)
    {
        $parameters = $request->all();

        $request->validate([
            'code' => 'required',
            'state' => '',
        ]);

        $result = Nightbot::getTokenData($parameters['code']);

        $array = [
            'code' => $parameters['code'],
            'state' => (empty($parameters['state']) ? '' : $parameters['state']),
            'access_token' => $result['access_token'],
            'expires_in' => $result['expires_in'],
            'refresh_token' => $result['refresh_token'],
            'token_type' => $result['token_type'],
            'scope' => $result['scope'],
        ];

        $nightbotId = NightbotToken::create($array);

        return Redirect::route('nightbot.twitch.success', compact('nightbotId'));
    }
}
