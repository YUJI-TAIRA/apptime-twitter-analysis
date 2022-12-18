<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;
use Exception;

class MsTwitterUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_twitter_users';
    protected $primaryKey = 'user_no';
    
    protected $guarded = [
        'user_no',
    ];
    protected $fillable = [
        'user_id',
        'user_username',
        'user_name',
        'user_description',
        'user_protected',
        'user_followers_count',
        'user_following_count',
        'user_tweet_count',
        'user_listed_count',
        'user_location',
        'user_url',
        'user_profile_image_url',
        'user_created_at',
    ];

    /**
     * ユーザー情報を保存
     * 
     * @param array $users
     * @return void
     */
    public static function saveUsers(array $users): void
    {
        // TODO: 仮実装 動作検証まだ ユーザー情報は少ないからupdateOrCreateでいいかも
        if (!isset($users)) {
            Log::warning('保存するユーザー情報がありません。');
            throw new Exception('保存するユーザー情報がありません。');
        }
        foreach ($users as $user) {
            Utils::addPrefixKeys($user, 'user_');
            self::updateOrCreate($user);
        }
        Log::info(count($users) . '件のユーザー情報を更新しました。');
    }

    /**
     * 先月のインセンティブ対象のツイート・ユーザーを取得
     * 
     * @param int $limitLikeCount
     * @return Collection
     */
    public static function getIncentiveTweets(int $limitLikeCount)
    {
        $firstDayPreviousMonth = new \DateTime('first day of previous month');
        $lastDayPreviousMonth = new \DateTime('last day of previous month');

        return self::with([
                'tbTwitterTweets' => fn($query) => $query
                    ->selectRaw('sum(case when tweet_like_count >= '. $limitLikeCount .'then'. $limitLikeCount .'.else tweet_like_count end) as likes_count')
                    ->selectRaw('sum(tweet_retweet_count) as retweets_count')
                    ->selectRaw('count(tweet_id) as tweets_count')
                    ->whereBetween('tweet_created_at', [$firstDayPreviousMonth, $lastDayPreviousMonth])
                    ->where('is_incentive_tweet', self::IS_INCENTIVE_TWEET_TRUE)
                    ->groupBy(Tables::TB_TWITTER_TWEETS.'.tweet_author_id')
                    ->orderByDesc('tweet_created_at'),
                'msEmployee' => fn($query) => $query
                    ->where('is_incentive_user', MsTwitterUser::IS_INCENTIVE_USER_TRUE)
                ])
                ->get();

    }
    /*
    * リレーション
    */
    public function tbTwitterTweets()
    {
        return $this->hasMany(TbTwitterTweet::class, 'user_id', 'tweet_author_id');
    }
    public function msEmployee()
    {
        return $this->hasOne(MsEmployee::class, 'user_id', 'user_id');
    }
    public function msTwitterList()
    {
        return $this->belongsToMany(MsTwitterList::class, 'ms_twitter_list_user', 'user_id', 'list_id')->withTimestamps();
    }

}
