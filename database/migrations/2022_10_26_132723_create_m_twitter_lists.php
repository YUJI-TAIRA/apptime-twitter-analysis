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
        Schema::create('m_twitter_lists', function (Blueprint $table) {
            $table->bigIncrements('list_no')->comment('リスト番号');
            $table->unsignedBigInteger('list_id')->unique()->nullable(false)->comment('リストID');
            $table->string('list_name', 50)->comment('リスト名');
            $table->integer('list_member_count')->comment('リストメンバー数');
            $table->integer('list_follower_count')->comment('リストフォロワー数');
            $table->boolean('list_private')->comment('非公開フラグ(true: 非公開 false: 公開)');
            $table->text('list_description')->comment('リスト説明');
            $table->unsignedBigInteger('list_owner_id')->comment('リストオーナーID');
            $table->timestamp('list_created_at')->comment('リスト作成日時');
            $table->boolean('is_incentive')->default(false)->comment('インセンティブ対象フラグ(true: 対象 false: 非対象)');
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
        Schema::dropIfExists('t_twitter_lists');
    }
};
