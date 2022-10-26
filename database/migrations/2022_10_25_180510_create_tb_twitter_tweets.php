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
            $table->string('tweet_id', 20)->primary()->comment('ツイートID');
            $table->string('author_id', 20)->nullable(false)->comment('ユーザーID');
            $table->string('list_id', 20)->comment('リストID');
            $table->text('text', 500)->comment('ツイート本文');
            $table->integer('retweet_count')->comment('リツイート数');
            $table->integer('reply_count')->comment('リプライ数');
            $table->integer('like_count')->comment('いいね数');
            $table->integer('quote_count')->comment('引用リツイート数');
            $table->string('lang', 10)->comment('言語');
            $table->boolean('is_deleted')->default(false)->comment('削除フラグ');
            $table->timestamp('tweeted_at')->nullable(false)->comment('ツイート日時');
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
        Schema::dropIfExists('tb_twitter_tweets');
    }
};
