<?php

namespace App\Services;

require "../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Consts\TwitterConst;
use Exception;
use Log;

/**
* Class TwitterApiService
*
* @package App\Services
*/

class TwitterApiService
{
    public TwitterOAuth $twitter;
    
    /**
     * TwitterApiService constructor
     */
    public function __construct()
    {
        $this->twitter = new TwitterOAuth(
            config('settings.twitter_api_key'),
            config('settings.twitter_api_key_secret'),
            config('settings.twitter_access_token'),
            config('settings.twitter_access_token_secret')
        );
        $this->twitter->setApiVersion(config('settings.twitter_api_version'));

        if (!isset($this->twitter)) {
            Log::critical('TwitterService@connect: Failed to connect to TwitterAPI');
            throw new Exception('TwitterAPIに接続出来ませんでした。');
        }
    }

    /**
     * 公開リストの情報を取得
     *  
     * @param string $listId
     * @return array|null
     */
    public function getPublicListInfo(string $listId): ?array
    {
        $params = [
            'list.fields' => 'created_at,description,follower_count,id,member_count,name,owner_id,private',
        ];
        $response = $this->twitter->get("lists/{$listId}", $params);
        $this->checkResponseError($response);
        return (array)$response->data;
    }

    /**
     * 公開リストからユーザー情報を取得
     * 
     * @param string $listId
     * @param int    $requestCount
     * @return array|null
     */
    public function getPublicListMembers(string $listId, int $requestCount): ?array
    {
        $members = [];
        $params = [
            'max_results' => TwitterConst::MAX_RESULTS,
            'user.fields' => 'id,created_at,description,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld',
        ];
        // 1リクエスト100件までのためループして結合
        for ($i = 0; $i < $requestCount; $i++) {
            $response = $this->twitter->get("lists/{$listId}/members", $params);
            $this->checkResponseError($response);
            $members = array_merge($members, $response->data);
            
            if (!isset($response->meta->next_token)) {
                return $members;
            }
            $params['pagination_token'] = $response->meta->next_token;
        }
        return $members;
    }
    
    /**
    * 公開リストからツイート情報を取得
    *
    * @param string $listId
    * @param int    $requestCount
    * @return array
    */
    public function getPublicListTweets(string $listId, int $requestCount): array
    {
        $tweets = [];
        $params = [
            'max_results' => TwitterConst::MAX_RESULTS,
            'tweet.fields' => 'created_at,author_id,public_metrics,lang',
        ];
        // 1リクエスト100ツイートまでのためループして結合
        for ($i = 0; $i < $requestCount; $i++) {
            $response = $this->twitter->get("lists/{$listId}/tweets", $params);
            $this->checkResponseError($response);
            $tweets = array_merge($tweets, $response->data);
            
            if (!isset($response->meta->next_token)) {
                return $tweets;
            }
            $params['pagination_token'] = $response->meta->next_token;
        }
        return $tweets;
    }

    /**
    * ユーザー情報を取得
    *
    * @param string $userId
    * @return array|null
    */
    public function getUserMetrics(string $userId): ?array
    {
        $params = [
            'user.fields' => 'public_metrics'
        ];
        $response = $this->twitter->get("users/{$userId}", $params);

        $this->checkResponseError($response);
        return (array)$response->data;
    }

    /**
     * レスポンスにエラーがあるかチェック
     * 
     * @param object $response
     * @return void
     */
    private function checkResponseError(object $response): void
    {
        if (isset($response->errors)) {
            // クラス名・メソッド名を呼び出し元より取得
            $cellerClass = debug_backtrace()[1]['class'];
            $callerFunction = debug_backtrace()[1]['function'];
            Log::error("{$cellerClass}@{$callerFunction}: {$response->errors[0]->message}"); 
            throw new Exception("{$cellerClass}@{$callerFunction}: {$response->errors[0]->message}");
        }
    }

    /**
     * TwitterApiService destructor
     */
    public function __destruct()
    {
        $this->twitter = null;
    }

}