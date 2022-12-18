<?php
namespace App\UseCases\Twitter;

use App\Services\TwitterApiService;
use App\Models\MsTwitterList;
use App\Models\MsTwitterUser;
use App\Models\TbTwitterTweet;
use Illuminate\Support\Facades\DB;
use Exception;

class SavePublicListTweetsUseCase
{
    protected $twitterApiService;
    
    public function __construct(TwitterApiService $twitterApiService)
    {
        $this->twitterApiService = $twitterApiService;
    }
    public function invoke()
    {
        // TODO: 仮実装 たぶん動かない
        // 後にcron化した時にこいつを呼ぶ
        try {
            // STEP1: 更新する対象のリストIDを取得 後に複数リスト対応出来るように最初から配列でやっておく
            $lists = MsTwitterList::getListIds();

            // STEP2: リストIDを元に各種情報を取得
            foreach ($lists as $list) {
                $list = $this->twitterApiService->getPublicListInfo($list['list_id']);
                $users = $this->twitterApiService->getPublicListMembers($list['list_id']);
                $tweets = $this->twitterApiService->getPublicListTweets($list['list_id']);

                // STEP3: 取得した情報をモデルに渡して保存
                DB::transaction(function () use ($list, $users, $tweets) {
                    foreach ($users as $user) {
                        MsTwitterUser::find($user['user_id'])->msTwitterList()->sync($list['list_id']);
                    }
                    MsTwitterList::saveList($list);
                    MsTwitterUser::saveUsers($users);
                    TbTwitterTweet::saveTweets($tweets);
                });
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}