<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;

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
        if (empty($users)) {
            Log::error('保存するユーザー情報がありません。');
            return;
        }
        foreach ($users as $user) {
            self::updateOrCreate($user);
        }
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
        return $this->belongsTo(MsEmployee::class, 'user_id', 'user_id');
    }
    public function msTwitterList()
    {
        return $this->belongsToMany(MsTwitterList::class, 'ms_twitter_list_user', 'user_id', 'list_id')->withTimestamps();
    }

}
