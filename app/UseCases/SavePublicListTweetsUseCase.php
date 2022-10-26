<?php
namespace App\UseCases;

use App\Models\Tweet;

#[Attribute]
class SavePublicListTweets
{
    protected $twitterService;
    
    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }
    public function __invoke()
    {
        // DBからツイートを取得
        $tweets = $this->twitterService->getPublicListTweets();

        foreach ($tweets as $tweet) {
            // TODO 保存処理
            Tweet::savetweet($tweet);
        }
    }
}