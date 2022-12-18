<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitterApiService;
use App\Consts\Consts;

class TwitterController extends Controller
{
    
    protected $twitter;

    public function __construct()
    {
        $this->twitter = new TwitterApiService();
    }
    // 投稿
    public function index(Request $request)
    {
        $tweets = $this->twitter->getPublicListTweets("1581302008174497794", 1);
        dd($tweets);
        return view('twitter', ['tweets' => $tweets]);
        // TODO ランキング取得
        // $ranking['followers'] = $twitter->getRanking(Consts::RANKING_FOLLOWERS);
        // $ranking['likes'] = $twitter->getRanking(Consts::RANKING_LIKES);
        // $ranking['impressions'] = $twitter->getRanking(Consts::RANKING_IMPRESSIONS);


    }
}