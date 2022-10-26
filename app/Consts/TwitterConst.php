<?php
namespace App\Consts;

class TwitterConst
{
    // TODO 定数はDBに保存し設定画面から任意に変更出来るようにする
    const MAX_RESULTS = 100;
    const TWEETS_REQUEST_COUNT = 3;
    const TWEETS_TOTAL_GET_COUNT = self::MAX_RESULTS * self::TWEETS_REQUEST_COUNT;
    const MEMBERS_REQUEST_COUNT = 1;
    const MEMBERS_TOTAL_GET_COUNT = self::MAX_RESULTS * self::MEMBERS_REQUEST_COUNT;
    const LIST_ID = '1581302008174497794';
    const ACTIVE_USER_DAYS = 90;

    // ランキング取得用
    const RANKING_FOLLOWERS = 1;
    const RANKING_LIKES = 2;
    const RANKING_IMPRESSIONS = 3;
}