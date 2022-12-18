<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_incentive_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('random_lottery_count')->comment('ランダム当選の人数');
            $table->integer('random_lottery_value')->comment('ランダム当選の1ツイートあたりのインセンティブ額');
            $table->integer('random_lottery_limit_count')->comment('ランダム当選のインセンティブ対象ツイート上限数');
            $table->integer('follower_value')->comment('1フォロワーあたりのインセンティブ額');
            $table->integer('follower_limit_count')->comment('インセンティブ対象フォロワー上限数');
            $table->integer('like_value')->comment('1いいねあたりのインセンティブ額');
            $table->integer('like_value_limit')->comment('いいねインセンティブの上限額');
            $table->integer('like_limit_count')->comment('1ツイートあたりのインセンティブ対象いいね上限数');
            $table->integer('seleciton_value')->comment('公式リツイート1回あたりのインセンティブ額');
            $table->integer('best_of_tweet_value')->comment('ベストオブツイート賞のインセンティブ額');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_incentive_settings');
    }
};
