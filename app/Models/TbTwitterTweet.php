<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Utils;
use App\Consts\Consts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

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
        'tweet_type',
        'tweet_is_reply',
        'tweet_source_tweet_id',
        'tweet_created_at',
        'is_incentive_tweet',
        'is_best_of_tweet',
    ];

    const TWEET_TYPE_TWEET         = 0; // 通常ツイート
    const TWEET_TYPE_RETWEET       = 1; // リツイート
    const TWEET_TYPE_QUOTE         = 2; // 引用ツイート
    const IS_REPLY_TRUE            = 1; // 返信ツイート
    const IS_REPLY_FALSE           = 0; // 返信ツイート以外
    const IS_INCENTIVE_TWEET_TRUE  = 1; // インセンティブ対象ツイート
    const IS_INCENTIVE_TWEET_FALSE = 0; // インセンティブ対象外ツイート
    const IS_BEST_OF_TWEET_TRUE    = 1; // ベストツイート
    const IS_BEST_OF_TWEET_FALSE   = 0; // ベストツイート以外
  
    /**
     * ツイート情報を取得
     * 引数にツイート日時を指定しその日時以降のツイートを取得
     * 
     * @param string|null $firstTweetCreatedAt ツイート取得対象開始日時
     * @return array
     */
    public static function getTweets(string $firstTweetCreatedAt = null): array
    {
        return self::when(isset($firstTweetCreatedAt), fn($query) => 
                $query->where('tweet_created_at', '>=', $firstTweetCreatedAt)
            )
            ->orderByDesc('tweet_created_at')
            ->limit(Consts::TWEETS_TOTAL_COUNT)
            ->get()
            ->toArray();
    }

    /**
     * 指定したアカウントが先月リツイートしたツイートを取得
     * 
     * @param string $authorId
     * @return array
     */
    public static function getSelecitionTweets(string $authorId)
    {
        $firstDayPreviousMonth = new \DateTime('first day of previous month');
        $lastDayPreviousMonth = new \DateTime('last day of previous month');

        return self::where(Tables::TB_TWITTER_TWEETS.'.tweet_author_id', $authorId)
            ->whereBetween(Tables::TB_TWITTER_TWEETS.'.tweet_created_at', [$firstDayPreviousMonth, $lastDayPreviousMonth])
            ->whereIn(Tables::TB_TWITTER_TWEETS.'.tweet_type', [self::TWEET_TYPE_RETWEET, self::TWEET_TYPE_QUOTE])
            // 自己結合でリツイート元のツイート投稿者IDを取得
            ->join(Tables::TB_TWITTER_TWEETS.'. as source_tweets', 'source_tweets.tweet_author_id', '=', Tables::TB_TWITTER_TWEETS.'.tweet_source_tweet_id')
            ->select(Tables::TB_TWITTER_TWEETS.'.*', 'source_tweets.tweet_author_id as source_author_id')
            ->orderByDesc(Tables::TB_TWITTER_TWEETS.'.tweet_created_at')
            ->get();

    }

    /**
     * ツイート情報を保存or更新or削除
     * 
     * @param array $tweets
     * @return void
     */
    public static function saveTweets(array $tweets): void
    {
        if (!isset($tweets)) {
            Log::warning('保存するツイート情報がありません。');
            throw new Exception('保存するツイート情報がありません。');
        }
        // $tweetsにprefixをつける
        foreach ($tweets as $key => $tweet) {
            Utils::addPrefixKeys($tweet, 'tweet_');
            $tweets[$key] = $tweet;
        }
        $tweets = Utils::sortArrayByKey($tweets, 'tweet_created_at', 'desc');
        $firstTweetCreatedAt = end($tweets)['tweet_created_at'];

        $dbTweets = self::getTweets($firstTweetCreatedAt);

        // DBに存在しないツイートは新規追加
        $newTweets = array_udiff($tweets, $dbTweets, fn($a, $b) => $a['tweet_id'] <=> $b['tweet_id']);

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
        $deleteTweets = array_udiff($dbTweets, $tweets, fn($a, $b) => $a['tweet_id'] <=> $b['tweet_id']);

        // new tweetsとupdate tweetsを結合してupsert
        $upsertTweets = array_merge($newTweets, $updateTweets);
        if (isset($upsertTweets)) {
            self::upsert($upsertTweets, ['tweet_id']);
            Log::info(count($upsertTweets) . '件のツイートを保存しました。');
        }
        
        // 削除
        if (isset($deleteTweets)) {
            self::whereIn('tweet_id', array_column($deleteTweets, 'tweet_id'))
                ->delete();
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
