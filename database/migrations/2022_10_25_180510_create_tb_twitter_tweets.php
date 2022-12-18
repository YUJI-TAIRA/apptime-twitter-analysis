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
        Schema::create('tb_twitter_tweets', function (Blueprint $table) {
            $table->bigIncrements('tweet_no')->comment('ツイート番号');
            $table->unsignedBigInteger('tweet_id')->unique()->nullable(false)->comment('ツイートID');
            $table->unsignedBigInteger('tweet_author_id')->nullable(false)->comment('ユーザーID');
            $table->text('tweet_text', 500)->comment('ツイート本文');
            $table->integer('tweet_retweet_count')->comment('リツイート数');
            $table->integer('tweet_reply_count')->comment('リプライ数');
            $table->integer('tweet_like_count')->index()->comment('いいね数');
            $table->integer('tweet_quote_count')->comment('引用リツイート数');
            $table->string('tweet_lang', 10)->comment('言語');
            $table->integer('tweet_type')->default(0)->comment('ツイート種別(0: 通常ツイート 1: リツイート 2: 引用リツイート)');
            $table->unsignedBigInteger('tweet_source_tweet_id')->nullable()->comment('ツイート元ツイートID');
            $table->boolean('tweet_is_reply')->default(false)->comment('リプライフラグ(1: リプライ 0: 通常ツイート)');
            $table->timestamp('tweet_created_at')->index()->nullable(false)->comment('ツイート日時');
            $table->boolean('is_incentive_tweet')->default(true)->comment('インセンティブ対象ツイートフラグ(1: 対象 0: 非対象)');
            $table->boolean('is_best_of_tweet')->default(false)->comment('ベスト・オブ・ツイートフラグ(1: ベスト・オブ・ツイート 0: 通常ツイート)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_twitter_tweets');
    }
};
