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
        Schema::create('t_twitter_tweets', function (Blueprint $table) {
            $table->unsignedBigInteger('tweet_id')->primary()->comment('ツイートID');

            // $table->foreignId('tweet_author_id')
            //     ->constrained('t_twitter_users', 'user_id')
            //     ->cascadeOnUpdate()
            //     ->comment('ユーザーID');

            // 制約をつけると後々の機能拡張時に

            $table->text('tweet_text', 500)->comment('ツイート本文');
            $table->integer('tweet_retweet_count')->comment('リツイート数');
            $table->integer('tweet_reply_count')->comment('リプライ数');
            $table->integer('tweet_like_count')->comment('いいね数');
            $table->integer('tweet_quote_count')->comment('引用リツイート数');
            $table->string('tweet_lang', 10)->comment('言語');
            $table->timestamp('tweet_created_at')->nullable(false)->comment('ツイート日時');
            $table->boolean('is_deleted')->default(false)->comment('削除フラグ');
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
        Schema::dropIfExists('t_twitter_tweets');
    }
};
