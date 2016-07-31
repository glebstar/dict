<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumnToDict extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict', function (Blueprint $table) {
            $table->integer('order')->default(30000);
        });

        DB::select('UPDATE `dict` SET `order` = `id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict', function ($table) {
            $table->dropColumn('order');
        });
    }
}
