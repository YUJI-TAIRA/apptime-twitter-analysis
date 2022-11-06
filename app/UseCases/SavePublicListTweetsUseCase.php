<?php
namespace App\UseCases;

use App\Services\TwitterApiService;
use App\Models\MsTwitterList;
use App\Models\MsTwitterUser;
use App\Models\TbTwitterTweet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\Utils;
use PDOException;
use Exception;

class SavePublicListTweets
{
    protected $twitterApiService;
    
    public function __construct(TwitterApiService $twitterApiService)
    {
        $this->twitterApiService = $twitterApiService;
    }
    public function __invoke()
    {
        // TODO: 仮実装 動かない
        // 後にcron化した時にこいつを呼ぶ

        // STEP1: 更新する対象のリストIDを取得 後に複数リスト対応出来るように最初から配列でやっておく
        $listIds = MsTwitterList::getListIds();

        if (empty($listIds)) {
            Log::error('更新対象のリストIDがありません。');
            return;
        }

        // STEP2: リストIDを元に各種情報を取得
        foreach ($listIds as $listId) {
            echo ('start update list_id: ' . $listId);
            $list = $this->twitterApiService->getPublicListInfo($listId);
            $users = $this->twitterApiService->getPublicListMembers($listId);
            $tweets = $this->twitterApiService->getPublicListTweets($listId);

            // STEP3: 取得した情報を保存用にprefixをつける
            Utils::addPrefixKeys($list, 'list_');
            Utils::addPrefixKeys($users, 'user_');
            Utils::addPrefixKeys($tweets, 'tweet_');

            // STEP4: 取得した情報をモデルに渡して保存
            DB::transaction(function () use ($list, $users, $tweets, $listId) {
                foreach ($users as $user) {
                    MsTwitterUser::find($user['user_id'])->msTwitterList()->sync($listId);
                }
                MsTwitterList::saveList($list);
                MsTwitterUser::saveUsers($users);
                TbTwitterTweet::saveTweets($tweets);
            });
            // STEP5: ログ出力
            Log::info('リストID: ' . $listId . 'の情報を更新しました。');
            Log::info(count($users) . '件のユーザー情報を更新しました。');
            // ツイート情報は複数分岐するためModel内でログ出力(仮)
        }
    }
}