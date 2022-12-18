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
        Schema::table('tb_monthly_incentive_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('twitter_id',)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_monthly_incentive_logs', function (Blueprint $table) {
            $table->string('twitter_id',)->change();
        });
    }
};
