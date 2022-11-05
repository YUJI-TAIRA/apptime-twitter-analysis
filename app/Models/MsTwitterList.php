<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\Utils;

class MsTwitterList extends Model
{
    use HasFactory;

    protected $table = 'ms_twitter_lists';
    protected $primaryKey = 'list_no';

    protected $guarded = [
        'list_no',
    ];
    protected $fillable = [
        'list_id',
        'list_name',
        'list_member_count',
        'list_follower_count',
        'list_private',
        'list_description',
        'list_owner_id',
        'list_created_at',
        'is_incentive',
        'is_deleted',
    ];

    /**
     * list_idを配列で取得
     * 
     * @return array
     */
    public static function getListId(): array
    {
        return self::select('list_id')->get()->toArray();
    }

    /**
     * リスト情報を保存
     * 
     * @param array $lists
     * @return void
     */
    public static function saveLists(array $lists): void
    {
        // TODO: 仮実装 動作検証まだ
        if (empty($lists)) {
            Log::error('保存するリスト情報がありません。');
            return;
        }
        // 実行
        foreach ($lists as $list) {
            Utils::addPrefixKeys($list, 'list_');
            self::updateOrCreate($list);
        }
    }

    /*
    * リレーション
    */
    public function msTwitterUser()
    {
        return $this->belongsToMany(MsTwitterUser::class, 'ms_twitter_list_user', 'list_id', 'user_id');
    }
}
