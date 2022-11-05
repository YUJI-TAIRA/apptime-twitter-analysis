<?php
namespace App\UseCases;

use App\Models\MsTwitterList;
use App\Models\MsTwitterListUser;
use App\Models\MsTwitterUser;
use App\Models\TbTwitterTweet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SavePublicListTweets
{
    protected $twitterService;
    
    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }
    public function __invoke()
    {
        // TODO: 仮実装 動かない
        // 後にcron化した時にこいつを呼ぶ

        // STEP1: 更新する対象のリストIDを取得 後に複数リスト対応出来るように最初から配列でやっておく
        $listIds = MsTwitterList::getListId();

        // STEP2: リストIDを元に各種情報を取得
        foreach ($listIds as $listId) {
            $lists = $this->twitterService->getPublicListInfo($listId);
            $users = $this->twitterService->getPublicListMembers($listId);
            $tweets = $this->twitterService->getPublicListTweets($listId);
            // STEP3: 取得した情報をモデルに渡して保存
            DB::transaction(function () use ($lists, $users, $tweets) {
                // リスト情報を保存
                MsTwitterList::saveLists($lists);
                // ユーザー情報を保存
                MsTwitterUser::saveUsers($users);
                // ツイート情報を保存
                TbTwitterTweet::saveTweets($tweets);
                // リストユーザー情報を保存
                MsTwitterListUser::saveListUsers($lists, $users);
            });
            // STEP4: ログ出力
            Log::info('リストID: ' . $listId . 'の情報を更新しました。');
            Log::info(count($users) . '件のユーザー情報を更新しました。');
            // ツイート情報は複数分岐するためModel内でログ出力(仮)
        }
    }
}