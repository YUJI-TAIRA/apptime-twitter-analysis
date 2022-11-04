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
        Schema::create('m_twitter_list_managements', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->constrained('m_twitter_users', 'user_id')
                ->cascadeOnDelete()
                ->comment('Twitter ID');

            $table->foreignId('list_id')
                ->constrained('m_twitter_lists', 'list_id')
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
        Schema::dropIfExists('m_twitter_list_managements');
    }
};
