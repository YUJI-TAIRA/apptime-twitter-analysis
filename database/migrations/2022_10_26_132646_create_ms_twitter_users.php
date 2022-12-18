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
        Schema::create('ms_twitter_users', function (Blueprint $table) {
            $table->increments('user_no')->comment('ユーザー番号');
            $table->unsignedBigInteger('user_id')->unique()->nullable(false)->comment('ユーザーID');
            $table->string('user_username', 50)->comment('スクリーンネーム Ex: @twitter');
            $table->string('user_name', 50)->comment('表示ユーザー名');
            $table->text('user_description')->comment('ユーザー説明文');
            $table->boolean('user_protected')->comment('非公開フラグ(true: 非公開 false: 公開)');
            $table->integer('user_followers_count')->comment('フォロワー数');
            $table->integer('user_following_count')->comment('フォロー数');
            $table->integer('user_tweet_count')->comment('ツイート数');
            $table->integer('user_listed_count')->comment('いいね数');
            $table->string('user_location', 50)->comment('所在地');
            $table->string('user_url', 100)->comment('URL');
            $table->string('user_profile_image_url', 100)->comment('プロフィール画像URL');
            $table->timestamp('user_created_at')->comment('ユーザー作成日時');
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
        Schema::dropIfExists('ms_twitter_users');
    }
};
