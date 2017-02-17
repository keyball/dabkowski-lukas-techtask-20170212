<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Tools;


use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Class Twitter
 *
 * @package app\Tools
 */
class Twitter
{

    /**
     * @var TwitterOAuth
     */
    protected $twitterLib;

    /**
     * Twitter constructor.
     *
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string|null $oAuthToken
     * @param string|null $oAuthTokenSecret
     */
    public function __construct(
      string $consumerKey,
      string $consumerSecret,
      string $oAuthToken = null,
      string $oAuthTokenSecret = null
    ) {
        $this->twitterLib = new TwitterOAuth($consumerKey, $consumerSecret);
    }

    /**
     * Updates the status message
     *
     * @param string $message
     */
    public function updateStatus(string $message)
    {
        $this->twitterLib->post('statuses/update', ['status' => 'Hello there! I will get ' . $message . ' for lunch!']);
    }

}