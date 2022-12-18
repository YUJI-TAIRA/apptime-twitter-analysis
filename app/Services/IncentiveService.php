<?php

namespace App\Services;

use App\Consts\Consts;
use App\Helpers\Utils;
use App\Models\MsTwitterUser;
use App\Models\TbTwitterTweet;
use Exception;
use Log;

/**
 * Class IncentiveService
 *
 * @package App\Services
 */
class IncentiveService
{
	public function __construct()
	{
	}

	/**
	 * ツイート情報からインセンティブを算出
	 * 
	 * @return void
	 */
	public function calcIncentive()
	{
		try {
			$value = 
			$userList = MsTwitterUser::getIncentiveTweets(1000);
			$selectionTweets = TbTwitterTweet::getSelectionTweets();

			// $userListからランダムで5名を選択
			$lotteryUserList = $userList->random(5);
			$params = array();
			foreach ($userList as $user) {
				$params = [
					'month'                    => date('Ym', strtotime('-1 month')),
					'employee_name'            => $user->name,
					'twitter_id'               => $user->user_id,
					'incentive_total'          => 0,
					'incentive_like'           => $user->likes_count <= 5000 ? $user->likes_count * 1 : limit,
					'incentive_follower'       => $user->followers_count > 99999 ? 0 : $user->followers_count * 0, // TODO: 仮実装
					'incentive_random_lottery' => $lotteryUserList->contains($user) ? ($user->tweets_count > 30 ? 3000 : $user->tweets_count * 100) : 0,
					'incentive_selection'      => $selectionTweets->where('source_author_id', $user->user_id)->count() * 1000,
					'incentive_best_of_tweet'  => $user->best_of_tweets_count * 1000,
				];
				$params['incentive_total'] = 
					$params['incentive_like'] + 
					$params['incentive_follower'] + 
					$params['incentive_random_lottery'] + 
					$params['incentive_selection'] + 
					$params['incentive_best_of_tweet'];
				TbMonthlyIncentiveLog::create($params);
			}
		} catch (Exception $e) {
			Log::error('IncentiveService@calcIncentive: インセンティブ算出処理でエラーが発生しました。');
			Log::error($e->getMessage());
		}
	}
}