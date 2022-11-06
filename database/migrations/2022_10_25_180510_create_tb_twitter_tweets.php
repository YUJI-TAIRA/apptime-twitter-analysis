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
            $table->timestamp('tweet_created_at')->index()->nullable(false)->comment('ツイート日時');
            $table->boolean('is_incentive_tweet')->default(true)->comment('インセンティブ対象ツイートフラグ(true: 対象 false: 非対象)');
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
