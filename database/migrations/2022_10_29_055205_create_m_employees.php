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
        Schema::create('m_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->primary()->autoIncrement()->comment('社員ID');
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('Twitter ユーザーID');
            $table->string('name', 50)->comment('社員名');
            $table->string('email', 100)->comment('メールアドレス');
            $table->boolean('is_incentive')->default(true)->comment('インセンティブ対象フラグ(true: 対象 false: 非対象)');
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
        Schema::dropIfExists('m_employees');
    }
};
