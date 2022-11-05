<?php
namespace App\Consts;

class TwitterConst
{
    /* --------------------------------------
    * Twitter APIリクエスト情報
    * ---------------------------------------
    * /

    /*
    * Twitter API リスト情報取得パラメータ
    * list.fields
    * https://developer.twitter.com/en/docs/twitter-api/data-dictionary/object-model/lists
    */
    const LIST_FIELDS = 'created_at,description,follower_count,id,member_count,name,owner_id,private';

    /*
    * Twitter API ユーザー情報取得パラメータ
    * user.fileds
    * https://developer.twitter.com/en/docs/twitter-api/data-dictionary/object-model/user
    */
    const USER_FIELDS = 'id,created_at,description,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,withheld';

    /*
    * Twitter API ツイート情報取得パラメータ
    * tweet.fields
    * https://developer.twitter.com/en/docs/twitter-api/data-dictionary/object-model/tweet
    */
    const TWEET_FIELDS = 'created_at,public_metrics,lang,author_id';

    // Twitter APIリクエスト件数
    const MAX_RESULTS = 100; // 1リクエストの数(max:100)
    const TWEETS_REQUEST_COUNT = 3; // ツイート取得リクエスト回数
    const MEMBERS_REQUEST_COUNT = 1; // メンバー取得リクエスト回数

    const TWEETS_TOTAL_COUNT = TWEETS_REQUEST_COUNT * MAX_RESULTS; // ツイート取得リクエスト総数
    const MEMBERS_TOTAL_COUNT = MEMBERS_REQUEST_COUNT * MAX_RESULTS; // メンバー取得リクエスト総数

    // TODO: ランキング取得用
    const RANKING_FOLLOWERS = 1;
    const RANKING_LIKES = 2;
    const RANKING_IMPRESSIONS = 3;
}