<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWordFunctional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict', function (Blueprint $table) {
            $table->text('description')->default('');
            $table->integer('added_user_id', false, true)->default(0);
            $table->tinyInteger('is_moderation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict', function ($table) {
            $table->dropColumn('description');
            $table->dropColumn('added_user_id');
            $table->dropColumn('is_moderation');
        });
    }
}
