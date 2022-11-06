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
        Schema::create('ms_employees', function (Blueprint $table) {
            $table->bigIncrements('employee_id')->comment('社員ID');
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('Twitter ユーザーID');
            $table->string('name', 50)->comment('社員名');
            $table->string('email', 100)->comment('メールアドレス');
            $table->boolean('is_incentive_employee')->default(true)->comment('インセンティブ対象フラグ(true: 対象 false: 非対象)');
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
        Schema::dropIfExists('ms_employees');
    }
};
