<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitterService;
use App\Consts\TwitterConst;

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
        $tweets = $this->twitter->getPublicListInfo("1581302008174497794");
        dd($tweets);
        return view('twitter', ['tweets' => $tweets]);
        // TODO ランキング取得
        // $ranking['followers'] = $twitter->getRanking(TwitterConst::RANKING_FOLLOWERS);
        // $ranking['likes'] = $twitter->getRanking(TwitterConst::RANKING_LIKES);
        // $ranking['impressions'] = $twitter->getRanking(TwitterConst::RANKING_IMPRESSIONS);


    }
}