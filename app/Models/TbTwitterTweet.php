<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Utils;
use App\Consts\TwitterConst;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbTwitterTweet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tb_twitter_tweets';
    protected $primaryKey = 'tweet_no';

    protected $guarded = [
        'tweet_no',
    ];
    protected $fillable = [
        'tweet_id',
        'tweet_author_id',
        'tweet_text',
        'tweet_retweet_count',
        'tweet_reply_count',
        'tweet_like_count',
        'tweet_quote_count',
        'tweet_lang',
        'tweet_created_at',
        'is_incentive_tweet',
    ];

    const IS_INCENTIVE_TWEET_TRUE = 1;
    const IS_INCENTIVE_TWEET_FALSE = 0;

    /**
     * ツイート情報を取得
     * 引数にツイート日時を指定しその日時以降のツイートを取得
     * 
     * @param string|null $firstTweetCreatedAt ツイート取得対象開始日時
     * @return array
     */
    public static function getTweets(string $firstTweetCreatedAt = null): array
    {
        return self::select('*')
            ->where('tweet_created_at', '>=', $firstTweetCreatedAt)
            ->orderBy('tweet_created_at', 'desc')
            ->limit(TwitterConst::TWEETS_TOTAL_COUNT)
            ->get()
            ->toArray();
    }

    /**
     * ツイート情報を保存or更新or削除
     * 
     * @param array $tweets
     * @return void
     */
    public static function saveTweets(array $tweets): void
    {
        if (empty($tweets)) {
            Log::error('保存するツイート情報がありません。');
            return;
        }
        $tweets = Utils::sortArrayByKey($tweets, 'tweet_created_at', 'desc');
        $firstTweetCreatedAt = end($tweets)['tweet_created_at'];

        $dbTweets = self::getTweets($firstTweetCreatedAt);

        // DBに存在しないツイートは新規追加
        $newTweets = array_udiff($tweets, $dbTweets, function ($a, $b) {
            return $a['tweet_id'] <=> $b['tweet_id'];
        });

        // DBに存在し各項目に変化があったツイートを更新
        $updateTweets = array_udiff($tweets, $dbTweets, function ($a, $b) {
            if ($a['tweet_id'] === $b['tweet_id']) {
                switch (true) {
                    case $a['tweet_retweet_count'] !== $b['tweet_retweet_count']:
                    case $a['tweet_reply_count'] !== $b['tweet_reply_count']:
                    case $a['tweet_like_count'] !== $b['tweet_like_count']:
                    case $a['tweet_quote_count'] !== $b['tweet_quote_count']:
                        return 1;
                    default:
                        return 0;
                }
            }
            return 0;
        });

        // DBに存在しAPIから取得したツイートに含まれていないツイートは削除済みツイートと判定
        $deleteTweets = array_udiff($dbTweets, $tweets, function ($a, $b) {
            return $a['tweet_id'] <=> $b['tweet_id'];
        });

        // 新規保存
        if (isset($newTweets)) {
            foreach ($newTweets as $newTweet) {
                self::create($newTweet);
            }
            Log::info(count($newTweets) . '件のツイートを追加しました。');
        }
        // 更新
        if (isset($updateTweets)) {
            foreach ($updateTweets as $updateTweet) {
                self::where('tweet_id', $updateTweet['tweet_id'])
                    ->update($updateTweet);
            }
            Log::info(count($updateTweets) . '件のツイートを更新しました。');
        }
        // 削除
        if (isset($deleteTweets)) {
            foreach ($deleteTweets as $deleteTweet) {
                self::where('tweet_id', $deleteTweet['tweet_id'])
                    ->delete();
            }
            Log::info(count($deleteTweets) . '件のツイートを削除しました。');
        }
    }

    /*
    * リレーション
    */
    public function msTwitterUser()
    {
        return $this->belongsTo(MsTwitterUser::class, 'tweet_author_id', 'user_id');
    }
}
