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
        Schema::create('tb_monthly_incentive_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('month', 6)->comment('年月');
            $table->string('twitter_id', 50)->comment('ツイッターID');
            $table->string('employee_name', 50)->comment('社員名');
            $table->integer('incentive_total')->comment('インセンティブ総額');
            $table->integer('incentive_like')->comment('いいね数のインセンティブ額');
            $table->integer('incentive_follower')->comment('フォロワー数のインセンティブ額');
            $table->integer('incentive_random_lottery')->comment('ランダム当選のインセンティブ額');
            $table->integer('incentive_selection')->comment('公式リツイートのインセンティブ額');
            $table->integer('incentive_best_of_tweet')->comment('ベストオブツイート賞のインセンティブ額');
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
        Schema::dropIfExists('tb_monthly_incentive_logs');
    }
};
