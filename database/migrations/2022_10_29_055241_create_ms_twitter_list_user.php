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
        Schema::create('ms_twitter_list_user', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->constrained('ms_twitter_users', 'user_id')
                ->cascadeOnDelete()
                ->comment('Twitter ID');

            $table->foreignId('list_id')
                ->constrained('ms_twitter_lists', 'list_id')
                ->cascadeOnDelete()
                ->comment('リストID');
            
            // 複合主キーとする
            $table->primary(['user_id', 'list_id']);

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
        Schema::dropIfExists('ms_twitter_list_user');
    }
};
