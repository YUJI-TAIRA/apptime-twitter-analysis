<?php

namespace App\Helpers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Consts\Consts;
use App\Models\TbTwitterTweet;
use Exception;
use Log;

class Utils
{
    /**
     * 連想配列のキーにprefixを付与
     * 
     * @param array  $data
     * @param string $prefix
     * @return void
     */
    public static function addPrefixKeys(array &$data, string $prefix): void
    {
        array_walk($data, fn (&$value, $key) => $value = $prefix . $key);
    }

    /**
     * 配列内からpublic_metricsを展開して元の配列に結合
     * 
     * @param array $response
     * @return array
     */
    public static function shapingPublicMetrics(array $response): array
    {
        $public_metrics = array_column($response, 'public_metrics');

        array_walk($response, function (&$value, $key) use ($public_metrics) {
            $value = array_merge((array)$value, (array)$public_metrics[$key]);
            unset($value['public_metrics']);
        });
        return $response;
    }

    /**
     * TwitterAPIのレスポンス情報を整形する
     * 
     * @param array $tweets
     * @param bool  $isExcludeRetweet
     * @return array
     */
    public static function shapingResponse(array $tweets): array
    {
        // edit_history_tweet_idsを削除
        array_walk($tweets, function (&$tweet) { unset($tweet->edit_history_tweet_ids); });
        // referenced_tweetsが含まれている場合
        array_walk($tweets, function (&$tweet) {
            if (isset($tweet->referenced_tweets)) {
                $referenced_tweet = $tweet->referenced_tweets[0];
                // リツイートの場合
                if ($referenced_tweet->type === 'retweeted') {
                    $tweet->source_tweet_id = $referenced_tweet->id;
                    $tweet->type = TbTwitterTweet::TWEET_TYPE_RETWEET;
                    unset($tweet->referenced_tweets);
                // 引用ツイートの場合
                } elseif ($referenced_tweet->type === 'quoted') {
                    $tweet->source_tweet_id = $referenced_tweet->id;
                    $tweet->type = TbTwitterTweet::TWEET_TYPE_QUOTE;
                    unset($tweet->referenced_tweets);
                } else {
                    unset($tweet->referenced_tweets);
                }
            }
            // リプライの場合
            if (isset($tweet->in_reply_to_user_id)) {
                $tweet->is_reply = TbTwitterTweet::IS_REPLY_TRUE;
                unset($tweet->in_reply_to_user_id);
            }
            // public_metricsを展開して元のtweetsに結合
            $tweet = array_merge((array)$tweet, (array)$tweet->public_metrics);
            unset($tweet['public_metrics']);
            
        });
        return (array)$tweets;
    }

    /**
     * 連想配列のソートを実行
     * 
     * @param array  $data
     * @param string $sortKey
     * @param string $sortType
     * @return array
     */
    public static function sortArrayByKey(array $data, string $sortKey, string $sortType = 'asc'): array
    {
        $sortType = strtolower($sortType);
        if ($sortType === 'asc') {
            usort($data, fn($a, $b) => $a[$sortKey] <=> $b[$sortKey]);
        } elseif ($sortType === 'desc') {
            usort($data, fn($a, $b) => $b[$sortKey] <=> $a[$sortKey]);
        }
        return $data;
    }
}